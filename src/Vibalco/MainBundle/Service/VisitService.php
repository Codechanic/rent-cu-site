<?php

namespace Vibalco\MainBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Vibalco\MainBundle\Entity\Visit;
use Symfony\Component\Validator\Validator;

/**
 * Description of VisitService
 *
 * @author yosbel
 */
class VisitService
{
    private $validator;
    private $em;
    
    /**
     * Tiempo de espera minimo en segundos entre registros provenientes de una 
     * misma (ip, url)
     */
    private static $interval = 300; //5 min
    
    /**
     * 
     * @param Validator $validador
     * @param EntityManager $em
     */
    public function __construct($validador, $em) {
        $this->validator = $validador;
        $this->em = $em;        
    }
    
    public function registerVisit($entity, $ip, $url){
        $visit = new Visit(get_class($entity), $entity->getId(), $url, $ip);        
        
        $errores = $this->validator->validate($visit);         
        
        if(count($errores) > 0)
        {
            return null;
        }
        
        $rep = $this->em->getRepository('MainBundle:Visit');

        try{   
            $dbvisit = $rep->findVisit($ip, $url);           

            if(!$dbvisit){
                $this->em->persist($visit);
            }
            else if(static::isValid($visit, $dbvisit)){
                $dbvisit->setUpdateDate($visit->getUpdateDate());
                $dbvisit->incCount();
            }

            $this->em->flush();
        }
        catch(NonUniqueResultException $ex){ 
            //TODO log de error interno
        }                    
    }
    
    /**
     * Separado porque la logica de registro puede aumentar en dificultad
     * en dependencia de las reglas del negocio
     */
    public static function isValid(Visit $visit, Visit $dbvisit) 
    {
        $time = $visit->getUpdateDate()->getTimestamp();
        $timedb = $dbvisit->getUpdateDate()->getTimestamp();
        
        return $time - $timedb > static::$interval;
    }
}
