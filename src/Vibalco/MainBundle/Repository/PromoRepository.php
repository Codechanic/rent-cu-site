<?php

namespace Vibalco\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PromoRepository extends EntityRepository
{    
    public function findPromos($class, $count = 1)
    {
        $date = new \DateTime('now');
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->from($class, 'p')
           ->select('p')
           ->where(':date BETWEEN p.from_date AND p.to_date')
           ->orderBy('p.showoffset', 'ASC')
           ->setParameter('date', $date)
           ->setMaxResults($count)
        ;
        
        return $qb->getQuery()->getResult();
    }
}
