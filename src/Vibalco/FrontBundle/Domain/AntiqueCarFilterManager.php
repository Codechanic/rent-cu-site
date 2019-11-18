<?php

namespace Vibalco\FrontBundle\Domain;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of AntiqueCarFilterManager
 *
 * @author yosbel
 */
class AntiqueCarFilterManager extends FilterManager 
{   
    private $antiquecar_filtername = 'antiquecarfilter';

    private $antiquecar_repository = 'MainBundle:AntiqueCar';
    
    private $antiquecar_keys = array(
            'municipality' => array('class' => 'MainBundle:Municipality'),
            'province' => array('class' => 'MainBundle:Province'),
            'brand' => array('class' => 'MainBundle:AntiqueCarBrand'),
            'year' => array('list' => true),
            'price-min' => null,
            'price-max' => null,
            'hardcover' => null,
            'convertible' => null,
            'search' => null,
    );
    
    private $antiquecar_order = array(
            'rank' => 'DESC'
    );  
    
    public function __construct(Session $session, EntityManager $em) 
    {        
        parent::__construct($session, $em, 
            $this->antiquecar_filtername, 
            $this->antiquecar_keys, 
            $this->antiquecar_order,
            $this->antiquecar_repository
        );                
    }
}
