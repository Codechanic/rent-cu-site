<?php
 namespace Vibalco\FrontBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
 



class SitemapController extends Controller {
    /**
     * @Route("/sitemap.{_format}", name="sitemap", defaults = {"_format" = "xml"})
     * 
     */
    public function sitemapAction() {
        $em = $this->getDoctrine()->getManager();

        $urls = array();
        $hostname = $this->getRequest()->getSchemeAndHttpHost();
        $languages = ["en", "es"];
        // incluye url página inicial
        foreach ($languages as $lang) {
            
            $urls[] = array(
                'loc' => $this->get('router')->generate('nowadays', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );


            $urls[] = array(
                'loc' => $this->get('router')->generate('render_about', array('_locale' => $lang)),
                'changefreq' => 'weekly',
                'priority' => '1.0'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('front_homestays', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('front_hotels', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('front_cars', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('sightseeing', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('contact', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('render_about', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $homestays = $em->getRepository('MainBundle:Homestay')->findAll();

            foreach ($homestays as $item) {
                $urls[] = array(
                    'loc' => $this->get('router')->generate('front_homestays_detail', array(
                        'slug' => $item->getSlug(), '_locale' => $lang
                    )),
                    'priority' => '0.5'
                );
            }
            $hotels = $em->getRepository('MainBundle:Hotel')->findAll();

            foreach ($hotels as $item) {
                $urls[] = array(
                    'loc' => $this->get('router')->generate('front_hotel_detail', array(
                        'slug' => $item->getSlug(), '_locale' => $lang
                    )),
                    'priority' => '0.5'
                );
            }
            $articles = $em->getRepository("ContenBundle:Article")->findBy(array('menu' => 1));
            
                foreach ($articles as $item) {
                $urls[] = array(
                    'loc' => $this->get('router')->generate('render_article_promotion', array(
                        'slug' => $item->getSlug(), '_locale' => $lang
                    )),
                    'priority' => '0.5'
                );
            }
            
             $articles = $em->getRepository("ContenBundle:Article")->findBy(array('menu' => 2));
            
                foreach ($articles as $item) {
                $urls[] = array(
                    'loc' => $this->get('router')->generate('render_article_promotion', array(
                        'slug' => $item->getSlug(), '_locale' => $lang
                    )),
                    'priority' => '0.5'
                );
            }
           
        }

        // incluye urls multiidioma
//        foreach($languages as $lang) {
//            $urls[] = array(
//                'loc' => $this->get('router')->generate('web_quienes_somos', array(
//                    '_locale' => $lang
//                )), 
//                'changefreq' => 'monthly', 
//                'priority' => '0.3'
//            );
//
//            $urls[] = array(
//                'loc' => $this->get('router')->generate('web_contacto', array(
//                    '_locale' => $lang
//                )), 
//                'changefreq' => 'monthly', 
//                'priority' => '0.3'
//            );
//
//            // ...
//        }
//        
//        // incluye urls desde base de datos
//        $categorias = $em->getRepository('MiBundle:Categoria')->findAll();
//        foreach ($categorias as $item) {
//            $urls[] = array(
//                'loc' => $this->get('router')->generate('web_categoria', array(
//                    'slug' => $item->getSlug()
//                )), 
//                'priority' => '0.5'
//            );
//        }
//
//        $productos = $em->getRepository('MiBundle:Producto')->findAll();
//        foreach ($productos as $item) {
//            $urls[] = array(
//                'loc' => $this->get('router')->generate('web_producto_detalle', array(
//                    'slug' => $item->getSlug()
//                )), 
//                'priority' => '0.5'
//            );
//        }

        return $this->render('FrontBundle:Default:sitemap.xml.twig', array(
                    'urls' => $urls,
                    'hostname' => $hostname
        ));
    }
}
