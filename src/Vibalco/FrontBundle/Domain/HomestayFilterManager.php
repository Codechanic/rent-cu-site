<?php

namespace Vibalco\FrontBundle\Domain;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of HomestayFilterManager
 *
 * @author yosbel
 */
class HomestayFilterManager extends FilterManager 
{   
    private $homestay_filtername = 'homestayfilter';

    private $homestay_repository = 'MainBundle:Homestay';
    
    private $homestay_keys = array(
            'municipality' => array('class' => 'MainBundle:Municipality'),
            'province' => array('class' => 'MainBundle:Province'),
            'acommodation' => array('class' => 'MainBundle:AcommodationType'),
            'service' => array('class' => 'MainBundle:HomestayFreeService', 'multiple' => true),
            'search' => null,
            'price-min' => null,
            'price-max' => null,
            'rooms' => array('list' => true),
    );
    
    private $homestay_order = array(
            'rank' => 'DESC'
    );  
    
    public function __construct(Session $session, EntityManager $em) 
    {        
        parent::__construct($session, $em, 
            $this->homestay_filtername, 
            $this->homestay_keys, 
            $this->homestay_order,
            $this->homestay_repository
        );                
    }

    public function set($key, $value) 
    {
        $filter = $this->getFilter();
        if($key == 'municipality' && isset($filter['province'])) {
            $this->remove('province');
        }
        else if( $key == 'province' && isset($filter['municipality'])) {
            $this->remove('municipality');
        }

        return parent::set($key, $value);
    }
}
