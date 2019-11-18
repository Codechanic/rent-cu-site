<?php

namespace Vibalco\FrontBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/{_locale}", defaults={"_locale" = "en"}, requirements={"_locale" = "|en|es"})
 */
class RedirectController extends Controller {
    /**
     * 
     * @Route("/redirect/{concept}/{id}", name="redirectpromo")
     * @Template("FrontBundle:Redirect:redirect.html.twig")
     */
    public function redirectPromoAction($concept, $id)
    {
        $message = 'front.redirect.errors';
        
        $class = $this->resolveClass($concept);
        
        if(!$class) {
            return array('message' => $message); 
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($class)->find($id);
        
        if($entity){
            $this->registerVisit($entity);
            
            $url = $entity->getUrl();
            
            if(!$url && $entity->getHomestay()){
               $url = $this->generateUrl('homestay', array('slug' => $entity->getHomestay()->getSlug()));
            }
            
            if($url) {                
                return $this->redirect($url);
            }
        }
        else {
            $message = 'front.redirect.notfound';
        }
        
        return array('message' => $message);
    }
        
    private function registerVisit($entity) 
    {
        $request = $this->getRequest();
        $service = $this->get('visit');
        
        $ip = $request->getClientIp();
        $url = $request->getPathInfo();
        
        $service->registerVisit($entity, $ip, $url);       
    }
    
    private function resolveClass($concept) {        
        switch($concept){
            case 'promo':
                return 'MainBundle:Promo';
            case 'promotop':
                return 'MainBundle:PromoTop';
        }
        
        return null;
    }
    
//    
//    /**
//     * 
//     * @Route("/redirect/{concept}/{id}", name="redirect")
//     * @Template()
//     */
//    public function redirectAction($concept, $id)
//    {
//        $class = $this->resolveClass($concept);
//        
//        $message = 'front.redirect.errors';
//        
//        if(class_exists($class)){
//            $em = $this->getDoctrine()->getManager();
//            $entity = $em->getRepository($class)->find($id);
//            
//            if($entity){                
//                $this->registerVisit($entity);
//                
//                return $this->redirect($entity->getUrl());
//            }
//            else {
//                $message = 'front.redirect.notfound';
//            }
//            
//        }
//        else {
//            $message = 'front.redirect.notfound';
//        }
//        
//        return array('message' => $message);
//    }    
}
