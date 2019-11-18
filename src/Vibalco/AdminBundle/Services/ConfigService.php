<?php
 
namespace Vibalco\AdminBundle\Services;

use Doctrine\ORM\EntityManager;
use Vibalco\AdminBundle\Entity\Settings;

class ConfigService
{   
    protected $data;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
        
        $entities = $this->em->getRepository('AdminBundle:Settings')->findBy(array(), array(), 1);        
        $this->data = count($entities) > 0 ? $entities[0] : new Settings();
    }

    public function getData()
    {         
        return  $this->data;
    }
}