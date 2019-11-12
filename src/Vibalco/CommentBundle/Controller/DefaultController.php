<?php

namespace Vibalco\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vibalco\CommentBundle\Entity\Contact;
use Vibalco\CommentBundle\Form\ContactTypeFront;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name) {
        return array('name' => $name);
    }
    /**
     * @Route("/subscribe/form", name="subscribe_form")
     * @Template()
     */
    public function formAction() {
        $entity = new Contact();
        $form = $this->createForm(new ContactTypeFront(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{_locale}/subscribe", name="subscribe")
     * @Template("CommentBundle:Default:confirmed.html.twig")
     */
    public function registerAction() {
        $entity = new Contact();
        $form = $this->createForm(new ContactTypeFront(), $entity);
        $request = $this->getRequest();
        $form->bind($request);
       

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository("CommentBundle:Contact")->findBy(array('email' => $form->getData()));
            if ($exist) {
                if ($exist[0]->getEnabled()) {
                    $mensaje = $this->get('translator')->trans('news.messages.registred');
                    $this->get('session')->getFlashBag()->add(
                            'error', $mensaje
                    );

                    $referer = $this->getRequest()->headers->get('referer');
                    return new \Symfony\Component\HttpFoundation\RedirectResponse($referer);
                } else {
                    $entity = $exist[0];
                }
            }
            try {
                $token = md5(uniqid(rand(), true));
                $entity->setToken($token);
                $entity->setEnabled(false);
                $em->persist($entity);
                $em->flush();
               
                $result['id'] = $entity->getId();
                
              //  Imprimir Vista Previa de Suscripcion
              /* return $this->render(
                                'CommentBundle:Default:email.html.twig', array('token' => $token)
                        );*/
                $message = \Swift_Message::newInstance()
                        ->setSubject('Vibalco')
                        ->setFrom(array($this->get('service_container')->getParameter('mailer_sender_email') => $this->get('service_container')->getParameter('mailer_sender_name')))
                        ->setTo($entity->getEmail())
                        ->setBody(
                        $this->renderView(
                                'CommentBundle:Default:email.html.twig', array('token' => $token)
                        ), 'text/html'
                        )
                ;
                $this->get('mailer')->send($message);
                $mensaje = $this->get('translator')->trans('news.messages.sended1');

                $this->get('session')->getFlashBag()->add(
                        'success', $mensaje
                );

                $referer = $this->getRequest()->headers->get('referer');
                return new \Symfony\Component\HttpFoundation\RedirectResponse($referer);
            } catch (\Exception $ex) {
                /* $this->get('session')->getFlashBag()->add(
                  'error', 'Ha ocurrido algun error al enviar el correo.'
                  ); */
               
                $mensaje = $this->get('translator')->trans('news.messages.error');
                $this->get('session')->getFlashBag()->add('error', $mensaje);

                $referer = $this->getRequest()->headers->get('referer');
                return new \Symfony\Component\HttpFoundation\RedirectResponse($referer);
            }
        } else {
             
            return $this->redirect($this->generateUrl('index'));
        }

        return array('message' => $mensaje);
    }
    
    /**
     * @Route("/{_locale}/subscribe/{token}", name="comfirm_action")
     * @Template()
     */
    public function confirmedAction($token) {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository("CommentBundle:Contact")->findOneBy(array('token' => $token, 'enabled' => false));

        if ($contact) {
            $contact->setEnabled(true);
            $em->flush();
            $message = $this->get('translator')->trans('news.messages.confirmed');
            $this->get('session')->getFlashBag()->add('success', $message);
        } else {
            return $this->redirect($this->generateUrl('index'));
        }
        return $this->redirect($this->generateUrl('index'));
    }

    /**
     * @Route("/unsubscribe/{email}/{token}/", name="unsubscribe_action")
     */
    public function unsubscribeAction($email, $token) {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository("NewsBundle:Contact")->findOneBy(array('token' => $token, 'email' => $email, 'enabled' => true));

        if ($contact) {
            $em->remove($contact);
            $em->flush();

            $mensaje = $this->get('translator')->trans('news.messages.delete');
            $this->get('session')->getFlashBag()->add('success', $mensaje);
            return $this->redirect($this->generateUrl('index'));
        }

        $message = $this->get('translator')->trans('news.messages.error');
        $this->get('session')->getFlashBag()->add('error', $message);
        return $this->redirect($this->generateUrl('index'));
    }

    /**
     * @Route("/logo", name="logo")
     */
    public function logoAction() {
        $image = $file = readfile("logo.png");
        return new \Symfony\Component\HttpFoundation\Response($image, 200, array('Content-Type' => 'image/png'));
    }
    
       /**
     * @Route("/comment", name="comment")
     */
    public function commentAction(Request $request) {
       
        $entity = new \Vibalco\CommentBundle\Entity\CommentVisitors();
        $form = $this->createForm(new \Vibalco\CommentBundle\Form\CommentHomeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            try {
                $entity->setRead(false);
                $entity->setDate(new \DateTime());
                
                $em->persist($entity);
                $em->flush();
                
                echo json_encode(true);
                die;
            } catch (\Exception $ex) {
                 echo json_encode(false);
                die;
             
            }
        } else {
                echo json_encode(false);
                die;
        }

        echo json_encode(false);
         die;
    }
    
    
    
  
    

}
