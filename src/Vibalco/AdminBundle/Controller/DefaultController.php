<?php

namespace Vibalco\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/{_locale}", defaults={"_locale" = "en"}, requirements={"_locale" = "|en|es"})
 * 
 */
class DefaultController extends Controller {

    /**
     * @Route("/", name="admin_index")
     * @Template()
     */
    public function indexAction() {
        return array();
    }

    /**
     * @Route("/dashboard", name="admin_dashboard")
     * @Template()
     */
    public function dashboardAction() {


        return array();
    }

    /**
     * @Route("/hotelchains", name="admin_hotelchains")
     * @Template()
     */
    public function hotelchainsAction() {
        $em = $this->getDoctrine()->getManager();
        $chains = $em->getRepository("MainBundle:HotelChain")->findAll();
        
        return array(
            'chains' => $chains
        );
    }
}
