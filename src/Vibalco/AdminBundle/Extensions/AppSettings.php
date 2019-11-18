<?php
 
namespace Vibalco\AdminBundle\Extensions;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Vibalco\AdminBundle\Entity\Settings;

class AppSettings
{
    protected $session;
    protected $em;

    function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function getSetting()
    {         
        return  $this->em->getRepository('AdminBundle:Settings')->findOneBy(array("id"=>1));
    }
}