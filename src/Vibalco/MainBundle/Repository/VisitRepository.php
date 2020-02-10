<?php

namespace Vibalco\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * VisitRepository
 */
class VisitRepository extends EntityRepository
{
    /**
     * Encuentra una Visita que ya se encuentre registrada y que cumpla con los
     * parametros de busqueda especificados
     * 
     * @param type $ip Direccion Ip desde donde se realizo la peticion.
     * @param type $url Url de la peticion realizada. Ej /homestay/43
     * @return null
     */
    public function findVisit($ip, $url) 
    {
        $em = $this->getEntityManager();
        
        $query = $em->createQuery('
            SELECT v
            FROM MainBundle:Visit v
            WHERE v.url = :url
              AND v.ip  = :ip
            ORDER BY v.updateDate DESC
        ')
        ->setFirstResult(0)
        ->setMaxResults(1)
        ->setParameter('url', $url)
        ->setParameter('ip', $ip);
        
        try
        {
           return $query->getSingleResult();
        } 
        catch (NoResultException $ex) 
        {
            //nada para hacer
        }         
        
        return null;
    }
    
    public function findMoreVisited() {
        
        $query = $this->getEntityManager()->createQuery(" 
            SELECT v, SUM(v.count) AS ncount
            FROM MainBundle:Visit v 
                INNER JOIN MainBundle:Homestay h
                    WITH v.entityid = h.id
            WHERE v.entityclass LIKE '%Homestay%'
                AND h.enabled = true
            GROUP BY v.entityclass, v.entityid
            ORDER BY ncount DESC
        ")
        ->setMaxResults(6)
        ;
        
        return $query->getResult();
    }

    public function clearOutdated($days) {
        $dql = "
            DELETE FROM MainBundle:Visit v
            WHERE v.updateDate < :date
                AND v.entityclass LIKE '%Homestay%'
        ";
        $date = new \DateTime('-' . $days . 'days');
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('date', $date);
        return $query->execute();
    }
}
