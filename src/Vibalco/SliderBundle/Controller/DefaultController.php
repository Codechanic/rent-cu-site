<?php

namespace Vibalco\SliderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Slide controller.
 *
 * @Route("/{_locale}" , defaults={"_locale" = "en"})
 */
class DefaultController extends Controller {

    /**
     * Lists all Slide entities.
     *
     * @Route("/slider", name="slider")
     * @Template()
     */
    public function sliderAction() {
        $em = $this->getDoctrine()->getManager();
        $slider = $em->getRepository('SliderBundle:Slide')->findBy(array('enabled' => true));
        
      
        return array('slider' => $slider);
    }

}
