<?php

namespace Vibalco\FrontBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Firebase\JWT\JWT;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vibalco\AdminBundle\Entity\User;
use Vibalco\FrontBundle\Entity\Comment;
use Vibalco\MainBundle\Entity\Applicant;
use Vibalco\MainBundle\Entity\ContactUs;
use Vibalco\MainBundle\Entity\Subscriber;
use Vibalco\MainBundle\Form\ApplicantType;
use Vibalco\MainBundle\Form\ContactUsType;

/**
 * @Route("/{_locale}", defaults={"_locale" = "en"}, requirements={"_locale" = "|en|es"})
 */
class DefaultController extends Controller {
    
    /**
     * 
     * @Route("/maqueta", name="maqueta")
     * @Template()
     */
    public function index_maquetaAction() {
        return array();
    }
    
    /**
     * 
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction() {
        return array();
    }

    /**
     * 
     * @Route("/toppromo", name="cover_toppromo")
     * @Template()
     */
    public function cover_toppromoAction() {
        $promos = $this->findPromos('MainBundle:PromoTop', 1);
        $promo = (count($promos) > 0) ? $promos[0] : null;        
        return array('promo' => $promo);
    }

    /**
     * 
     * @Route("/stripslider", name="stripslider")
     * @Template()
     */
    public function cover_stripsliderAction() {
        $promos = $this->findPromos('MainBundle:PromoStrip', 5);        
        return array('promos' => $promos);
    }

    /**
     * 
     * @Route("/mainslider", name="mainslider")
     * @Template()
     */
    public function cover_mainsliderAction() {        
        $promos = $this->findPromos('MainBundle:PromoCover', 5);
        return array('promos' => $promos);
    }

    /**
     * 
     * @Route("/premiumlist", name="premiumlist")
     * @Template()
     */
    public function cover_premiumlistAction() {
        $promos = $this->findPromos('MainBundle:PromoPremium', 3);
        return array('promos' => $promos);
    }
    
    /**
     * 
     * @Route("/discountlist", name="discountlist")
     * @Template()
     */
    public function cover_discountsliderAction() {
        $promos = $this->findPromos('MainBundle:PromoDiscount', 6);      
        return array('promos' => $promos);
    }
    
    /**
     * 
     * @Route("/visitslider", name="visitslider")
     * @Template()
     */
    public function cover_visitsliderAction() {
        $em = $this->getDoctrine()->getManager();
                
        $visits = $em->getRepository('MainBundle:Visit')
                     ->findMoreVisited();
        
        $entities = new ArrayCollection();
        
        foreach ($visits as $v) {
            $entity = $em->getRepository($v[0]->getEntityClass())->find($v[0]->getEntityId());
            
            if($entity){
                $entities[] = $entity;
            }
        }
        
        return array(
            'entities' => $entities
        );
    }
    
    /**
     * @Route("/addsubscriber", name="addsubscriber")
     */
    public function addsubscriberAction(Request $request) 
    {        
        $email = $request->get('subscriberemail');
        $s = new Subscriber();
        $s->setEmail($email);
        $s->setEnabled(true);
        $s->setLocked(false);
        
        $validator = $this->container->get('validator');
        
        $errors = $validator->validate($s);
        
        if(count($errors) > 0){
            return new JsonResponse(array('success' => false));
        }

        try {
            $em = $this->getDoctrine()->getManager();
            
            $sdb = $em->getRepository('MainBundle:Subscriber')->findOneBy(array('email' => $email));

            if($sdb){
                if(!$sdb->getLocked()) {
                    $sdb->setEnabled(true);
                    $em->persist($sdb);
                    
                    //Send notification mail to site admins
                    $this->sendSubscriberMail($sdb);
                }
            }
            else {
                $em->persist($s);

                //Send notification mail to site admins
                $this->sendSubscriberMail($s);
            }

            $em->flush();
                        
            return new JsonResponse(array('success' => true));
        }
        catch(Exception $e) {}
        
        return new JsonResponse(array('success' => false));
    }
    
    /**
     * @Route("/addapplicant", name="addapplicant")
     * @Template
     */
    public function addapplicantAction(Request $request) 
    {
        $form = $this->createForm(new ApplicantType(), new Applicant());
        $msg = array('success' => '', 'error' => '');
        
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            
            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                $entity = $form->getData();
                try {
                    $em->persist($entity);
                    $em->flush();
                    
                    //Send notification mail to site admins
                    $this->sendApplicantMail($entity);

                    $msg['success'] = 'front.applicant.message.success';
                    $form = $this->createForm(new ApplicantType(), new Applicant());
                }
                catch(\Exception $e){
                    $msg['error'] = 'front.applicant.message.internalerror';
                }
            }
            else {
                $msg['error'] = 'front.applicant.message.error';
            }
        }
        
        return array('form' => $form->createView(), 'msg' => $msg);
    }
    
    /**
     * @Route("/contactus", name="contactus")
     * @Template
     */
    public function contactusAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AdminBundle:Settings')->findAll();        
        $entity = count($entities) > 0 ? $entities[0] : null;

        return array(
            'entity' => $entity
        );
    }
    
    /**
     * @Route("/contactusform", name="contactusform")
     * @Template
     */
    public function contactusformAction(Request $request) 
    {
        $form = $this->createForm(new ContactUsType(), new ContactUs());
        $msg = array('success' => '', 'error' => '');
        
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            
            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                $entity = $form->getData();
                try {
                    $em->persist($entity);
                    $em->flush();
                    
                    //Send notification mail to site admins
                    $this->sendContactUsMail($entity);
                    
                    $msg['success'] = 'front.contactus.message.success';
                    $form = $this->createForm(new ContactUsType(), new ContactUs());
                }
                catch(\Exception $e){
                    $msg['error'] = 'front.contactus.message.internalerror';
                }
            }
            else {
                $msg['error'] = 'front.contactus.message.error';
            }
        }
        
        return array('form' => $form->createView(), 'msg' => $msg);
    }
       
    /**
     * @Route("/externallinks", name="externallinks")
     * @Template
     */
    public function externallinksAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MainBundle:ExternalLink')
                      ->findBy(array(), array('norder' => 'ASC'));
        
        return array('entities' => $entities);
    }
    
    /**
     * @Route("/aboutus", name="aboutus")
     * @Template
     */
    public function aboutusAction() {
        return array();
    }
    
    /**
     * @Route("/cover_filter", name="cover_filter")
     * @Template
     */
    public function cover_filterAction() {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('
            SELECT p AS province, COUNT(p) AS ncount
            FROM MainBundle:Homestay h, MainBundle:Province p, MainBundle:Municipality m
            WHERE h.municipality = m AND m.province = p
            GROUP BY p
            ORDER BY ncount DESC
        ')
        ;             
        $homestayscount = $query->getResult();
        
        $list = array();
        
        foreach ($homestayscount as $e) {
            $list[$e['province']->getId()] = array(
                'province' => $e['province']->getName(), 
                'homestaycount' => $e['ncount']
            );
        }
        
        //TODO add car count here
        
        return array('list' => $list);
    }

    /**
     * @Route("/comment", name="rent.app.comment", methods={"GET"})
     * @param Request $request
     */
    public function listCommentAction(Request $request) {
        $list = $request->query->get('homeStayId');
        try {
            $em = $this->getDoctrine()->getManager();
            $homeStay = $em->getRepository('MainBundle:Homestay')->find($list);
            $comments = $em->getRepository('FrontBundle:Comment')->findBy(array(
                'homestay' => $homeStay,
                'enabled' => true,
            ));

            return new JsonResponse($comments);

        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *@Route("/comment", name="rent.app.comment.create", methods={"POST"})
     * @param Request $request
     */
    public function createComment(Request $request) {
        $homestay = $request->request->get('homeStayId');
        $name = $request->request->get('name');
        $text = $request->request->get('text');
        $email = $request->request->get('email');
        try {
            $em = $this->getDoctrine()->getManager();
            $homeStay = $em->getRepository('MainBundle:Homestay')->find($homestay);
            $comment = new Comment();
            $comment->setName($name);
            $comment->setText($text);
            $comment->setRating(0);
            $comment->setEnabled(false);
            $comment->setEmail($email);
            $comment->setHomestay($homeStay);
            $em->persist($comment);
            $em->flush();
            $mensaje = $this->get('translator')->trans('news.messages.registred');
            $message = \Swift_Message::newInstance()
                            ->setSubject($entity->getTitle())
                            ->setFrom(array($this->get('service_container')->getParameter('mailer_sender_email') => $this->get('service_container')->getParameter('mailer_sender_name')))
                            ->setTo('booking@rent.cu')
                  
                            ->setBody(
                            'Se ha realizado un comentario a una casa', 'text/html'
                            )
                    ;
                    $this->get('mailer')->send($message);
            return new JsonResponse();
        }  catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), 500);
        }

    }

    /**
     *@Route("/insmet", name="rent.app.insmet", methods={"GET"}, defaults={"_format"="xml"})
     *@param Request $request
     */
    public function getClimateAction(Request $request) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.insmet.cu/asp/genesis.asp?TB0=RSSFEED");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $response = new Response();
        $output = utf8_encode($output);
        $response->setContent($output);
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }

    /**
     * @Route("/token", name="_security_token", methods={"POST", "OPTIONS"})
     */
    public function tokenAction(Request $request){
        if ($request->isXmlHttpRequest()) {
            try {
                if ($request->getMethod() === 'OPTIONS') {
                    return new JsonResponse(null,200);
                }
                $username = $request->get('_username');
                $password = $request->get('_password');
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository('AdminBundle:User')->loadUserByUsername($username);
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                if (!empty($user)) {
                    $isValid = $encoder->isPasswordValid($encoder->encodePassword($password, $user->getSalt()), $password, $user->getSalt());
//                    $isValid = $encoder->isPasswordValid($user, $password);
                    if (!$isValid) {
                        return new JsonResponse(array('Bad Credentials'), 400);
                    }
                }
                $key = "secretKey";
                $token = array(
                    'username' => $user->getUsername(),
                    'sub' => $user->getId()
                );

                $jwt = JWT::encode($token, $key);

                return new JsonResponse($jwt, 200);
            } catch (\Exception $exception) {
                return new JsonResponse(array('Bad Credentials'), 400);
            }
        } else {
            return new RedirectResponse($this->get('router')->generate('homestays'));
        }
    }
    
    private function findPromos($class, $count) {
        $em = $this->getDoctrine()->getManager();   

        $promos = $em->getRepository('MainBundle:Promo')
           ->findPromos($class, $count);
        
        foreach ($promos as $promo) {
            $promo->incShowCount();
            $em->persist($promo);
        }
        
        $em->flush();
        
        return $promos;
    }
    
    //TODO add i10n for subject and body content in emails
    private function sendSubscriberMail(Subscriber $entity) {  
        $subject = $this->get('translator')->trans("front.subscriber.email.subject");
        $body = $this->renderView('FrontBundle:Email:subscriber.html.twig', array('entity' => $entity));

        $this->sendMail($body, $subject);
    }

    private function sendContactUsMail(ContactUs $entity) {
        $subject = $this->get('translator')->trans("front.contactus.email.subject");
        $body = $this->renderView('FrontBundle:Email:contactus.html.twig', array('entity' => $entity));

        $this->sendMail($body, $subject);
    }

    private function sendApplicantMail(Applicant $entity) {   
        $subject = $this->get('translator')->trans("front.applicant.email.subject");
        $body = $this->renderView('FrontBundle:Email:applicant.html.twig', array('entity' => $entity));

        $this->sendMail($body, $subject);
    }
    
    private function sendMail($body, $subject) {
        $config = $this->get('config');
        $settings = $config->getData();

        $from = $settings->getAdminemail();
        $to = array($settings->getEmail());
        
        foreach ($to as $key => $v) {
            if (empty($v)) {
                unset($to[$key]);                
            }
        }
        
        if(!empty($from) && count($to) > 0)
        {
            $modif_msg = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setContentType("text/html")
                ->setBody($body);

            $this->get('mailer')->send($modif_msg);
        }
    }
}
