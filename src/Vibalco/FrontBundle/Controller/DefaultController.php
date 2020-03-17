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
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Vibalco\AdminBundle\Entity\User;
use Vibalco\FrontBundle\Entity\Comment;
use Vibalco\MainBundle\Entity\Applicant;
use Vibalco\MainBundle\Entity\ContactUs;
use Vibalco\MainBundle\Entity\RefreshToken;
use Vibalco\MainBundle\Entity\Subscriber;
use Vibalco\MainBundle\Form\ApplicantType;
use Vibalco\MainBundle\Form\ContactUsType;

/**
 * @Route("/{_locale}", defaults={"_locale" = "en"}, requirements={"_locale" = "|en|es"})
 */
class DefaultController extends Controller
{
    protected $headers = array(
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => 'content-type,access-control-allow-origin,access-control-allow-headers,authorization',
        'Access-Control-Allow-Methods' => 'GET,HEAD,PUT,PATCH,POST,DELETE',
        'Connection' => 'keep-alive'
    );

    /**
     *
     * @Route("/maqueta", name="maqueta")
     * @Template()
     */
    public function index_maquetaAction()
    {
        return array();
    }

    /**
     *
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     *
     * @Route("/toppromo", name="cover_toppromo")
     * @Template()
     */
    public function cover_toppromoAction()
    {
        $promos = $this->findPromos('MainBundle:PromoTop', 1);
        $promo = (count($promos) > 0) ? $promos[0] : null;
        return array('promo' => $promo);
    }

    /**
     *
     * @Route("/stripslider", name="stripslider")
     * @Template()
     */
    public function cover_stripsliderAction()
    {
        $promos = $this->findPromos('MainBundle:PromoStrip', 5);
        return array('promos' => $promos);
    }

    /**
     *
     * @Route("/mainslider", name="mainslider")
     * @Template()
     */
    public function cover_mainsliderAction()
    {
        $promos = $this->findPromos('MainBundle:PromoCover', 5);
        return array('promos' => $promos);
    }

    /**
     *
     * @Route("/premiumlist", name="premiumlist")
     * @Template()
     */
    public function cover_premiumlistAction()
    {
        $promos = $this->findPromos('MainBundle:PromoPremium', 3);
        return array('promos' => $promos);
    }

    /**
     *
     * @Route("/discountlist", name="discountlist")
     * @Template()
     */
    public function cover_discountsliderAction()
    {
        $promos = $this->findPromos('MainBundle:PromoDiscount', 6);
        return array('promos' => $promos);
    }

    /**
     *
     * @Route("/visitslider", name="visitslider")
     * @Template()
     */
    public function cover_visitsliderAction()
    {
        $em = $this->getDoctrine()->getManager();

        $visits = $em->getRepository('MainBundle:Visit')
            ->findMoreVisited();

        $entities = new ArrayCollection();

        foreach ($visits as $v) {
            $entity = $em->getRepository($v[0]->getEntityClass())->find($v[0]->getEntityId());

            if ($entity) {
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

        if (count($errors) > 0) {
            return new JsonResponse(array('success' => false));
        }

        try {
            $em = $this->getDoctrine()->getManager();

            $sdb = $em->getRepository('MainBundle:Subscriber')->findOneBy(array('email' => $email));

            if ($sdb) {
                if (!$sdb->getLocked()) {
                    $sdb->setEnabled(true);
                    $em->persist($sdb);

                    //Send notification mail to site admins
                    $this->sendSubscriberMail($sdb);
                }
            } else {
                $em->persist($s);

                //Send notification mail to site admins
                $this->sendSubscriberMail($s);
            }

            $em->flush();

            return new JsonResponse(array('success' => true));
        } catch (Exception $e) {
        }

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

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $entity = $form->getData();
                try {
                    $em->persist($entity);
                    $em->flush();

                    //Send notification mail to site admins
                    $this->sendApplicantMail($entity);

                    $msg['success'] = 'front.applicant.message.success';
                    $form = $this->createForm(new ApplicantType(), new Applicant());
                } catch (\Exception $e) {
                    $msg['error'] = 'front.applicant.message.internalerror';
                }
            } else {
                $msg['error'] = 'front.applicant.message.error';
            }
        }

        return array('form' => $form->createView(), 'msg' => $msg);
    }

    /**
     * @Route("/contactus", name="contactus")
     * @Template
     */
    public function contactusAction()
    {
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

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $entity = $form->getData();
                try {
                    $em->persist($entity);
                    $em->flush();

                    //Send notification mail to site admins
                    $this->sendContactUsMail($entity);

                    $msg['success'] = 'front.contactus.message.success';
                    $form = $this->createForm(new ContactUsType(), new ContactUs());
                } catch (\Exception $e) {
                    $msg['error'] = 'front.contactus.message.internalerror';
                }
            } else {
                $msg['error'] = 'front.contactus.message.error';
            }
        }

        return array('form' => $form->createView(), 'msg' => $msg);
    }

    /**
     * @Route("/externallinks", name="externallinks")
     * @Template
     */
    public function externallinksAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MainBundle:ExternalLink')
            ->findBy(array(), array('norder' => 'ASC'));

        return array('entities' => $entities);
    }

    /**
     * @Route("/aboutus", name="aboutus")
     * @Template
     */
    public function aboutusAction()
    {
        return array();
    }

    /**
     * @Route("/cover_filter", name="cover_filter")
     * @Template
     */
    public function cover_filterAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('
            SELECT p AS province, COUNT(p) AS ncount
            FROM MainBundle:Homestay h, MainBundle:Province p, MainBundle:Municipality m
            WHERE h.municipality = m AND m.province = p
            GROUP BY p
            ORDER BY ncount DESC
        ');
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
    public function listCommentAction(Request $request)
    {
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
     * @Route("/comment", name="rent.app.comment.create", methods={"POST"})
     * @param Request $request
     */
    public function createComment(Request $request)
    {
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
            $body = $this->renderView('FrontBundle:Email:comment.html.twig', array('entity' => $comment));
            $this->sendMail($body, 'Se ha realizado un comentario');
            return new JsonResponse();
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), 500);
        }

    }

    /**
     * @Route("/insmet", name="rent.app.insmet", methods={"GET"}, defaults={"_format"="xml"})
     * @param Request $request
     * @return Response
     */
    public function getClimateAction(Request $request)
    {
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
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Response
     */
    public function tokenAction(Request $request)
    {

        $key = $this->get('service_container')->getParameter('secret');

        if ($request->getMethod() === 'OPTIONS') {
            return new Response(
                null,
                204,
                $this->headers);
        } elseif ($request->getMethod() === 'POST') {
            try {
                $username = $request->get('_username');
                $password = $request->get('_password');
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository('AdminBundle:User')->loadUserByUsername($username);
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                if (!empty($user)) {
                    $isValid = $encoder->isPasswordValid($encoder->encodePassword($password, $user->getSalt()), $password, $user->getSalt());

                    $isValid = $isValid && $this->checkPassword($user, $password);

                } else {
                    $isValid = false;
                }
                if (!$isValid) {
                    return new JsonResponse(array('Bad Credentials'), 401, $this->headers);
                }
                $issuedAt = time();
                $nbf = $issuedAt;
                $exp = $nbf + 7200;
                $token = $issuedAt . $username . $user->getSalt();
                $token = hash('sha256', $token);
                $refreshToken = new RefreshToken();
                $refreshToken->setToken($token);
                $refreshToken->setUser($user);
                $date = new \DateTime();
                $date->setTimestamp($exp);
                $refreshToken->setExpires($date);
                $user->addToken($refreshToken);
                $em->persist($user);
                $em->persist($refreshToken);
                $em->flush();
                $roles = $user->getRoles();
                $token = array(
                    'username' => $user->getUsername(),
                    'sub' => $user->getId(),
                    'role' => $roles[0]->getRole(),
                    'exp' => $exp,
                    'iat' => $issuedAt,
                    'nbf' => $nbf,
                    'refresh_token' => $refreshToken->getToken(),
                );

                $jwt = JWT::encode($token, $key);

                // Angular applications need these headers set in the response
                return new JsonResponse($jwt, 200, $this->headers);
            } catch (UsernameNotFoundException $exception) {
                return new JsonResponse(array('Bad credentials'), 401, $this->headers);
            } catch (\Exception $exception) {
                return new JsonResponse(array($exception->getMessage()), 500, $this->headers);
            }
        } else {
            return new RedirectResponse($this->get('router')->generate('homestays'));
        }
    }

    /**
     * @Route("/refresh", name="_refresh_token", methods={"POST", "OPTIONS"})
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Response
     */
    public function refreshAction(Request $request)
    {

        if ($request->getMethod() === 'OPTIONS') {
            return new Response(
                null,
                204,
                $this->headers);
        } elseif ($request->getMethod() === 'POST') {
            try {
                $key = $this->get('service_container')->getParameter('secret');
                $_token = $request->get('_token');
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository('AdminBundle:User')->getUserByToken($_token);
                $iat = time();
                $nbf = $iat;
                $exp = $nbf + 7200;
                $refreshToken = $iat . $user->getUsername() . $user->getSalt();
                $refreshToken = hash('sha256', $refreshToken);
                $token = new RefreshToken();
                $token->setUser($user);
                $token->setToken($refreshToken);
                $date = new \DateTime();
                $date->setTimestamp($exp);
                $token->setExpires($date);
                $user->addToken($token);
                $em->persist($user);
                $em->persist($token);
                $em->flush();
                $roles = $user->getRoles();
                $token = array(
                    'username' => $user->getUsername(),
                    'sub' => $user->getId(),
                    'role' => $roles[0]->getRole(),
                    'exp' => $exp,
                    'iat' => $iat,
                    'nbf' => $nbf,
                    'refresh_token' => $refreshToken,
                );

                $jwt = JWT::encode($token, $key);

                return new JsonResponse($jwt, 200,
                    $this->headers);

            } catch (\Exception $exception) {
                return new JsonResponse(array('Bad Credentials'), 400, $this->headers);
            }
        } else {
            return new RedirectResponse($this->get('router')->generate('homestays'));
        }

    }

    /**
     * @Route("/owner", name="_owner", methods={"POST", "OPTIONS"})
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Response
     */
    public function ownerAction(Request $request)
    {
        if ($request->getMethod() === 'OPTIONS') {
            return new Response(
                null,
                204,
                array(
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Headers' => 'content-type,access-control-allow-headers',
                    'Access-Control-Allow-Methods' => 'POST',
                    'Connection' => 'keep-alive'
                ));
        }

        if ($request->getMethod() === 'POST') {
            $username = $request->get('_username');
            $name = $request->get('_name');
            $password = $request->get('_password');

            $violations = array();
            if (empty($username)) {
                $violations[] = 'username';
            }

            if (empty($password)) {
                $violations[] = 'password';
            }

            if (empty($name)) {
                $violations[] = 'name';
            }

            if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $violations[] = 'username';
            }

            if (count($violations) > 0) {
                return new JsonResponse(
                    'Hay campos incorrectos',
                    400
                );
            }

            try {

                $em = $this->getDoctrine()->getManager();
                $adm = $em->getRepository('AdminBundle:User')->findBy(array('username' => $username));
                $role = $em->getRepository('AdminBundle:Role')->findBy(array('role' => "ROLE_USER"));
                if (count($role) <= 0) {
                    $role = new \Vibalco\AdminBundle\Entity\Role();
                    $role->setRole("ROLE_USER");
                    $role->setName("HOME OWNER");
                    $em->persist($role);
                    $em->flush();
                    $role = new ArrayCollection([$role]);

                }
                if (count($adm) <= 0) {
                    $user = new User();
                    $user->setUsername($username);
                    $user->setName($name);
                    $user->setEmail($username);
                    $user->setPassword($password);
                    $user->setRoles($role);
                    $secured = $this->getSecurePassword($user);
                    $user->setPassword($secured);
                    $em->persist($user);
                    $em->flush();
                    return new JsonResponse(
                        $username,
                        201,
                        array(
                            'Access-Control-Allow-Origin' => '*',
                            'Access-Control-Allow-Headers' => 'content-type',
                            'Access-Control-Allow-Methods' => 'POST,OPTIONS',
                            'Connection' => 'close'
                        )
                    );

                } else {
                    return new JsonResponse(
                        'El usuario ya existe',
                        409,
                        array(
                            'Access-Control-Allow-Origin' => '*',
                            'Access-Control-Allow-Headers' => 'content-type',
                            'Access-Control-Allow-Methods' => 'POST,OPTIONS',
                            'Connection' => 'close'
                        )
                    );
                }

            } catch (Exception $exception) {
                return new JsonResponse(
                    $exception->getMessage(),
                    500
                );
            }
        }

    }

    /**
     * @Route("/password/{id}", name="_owner_update_password", methods={"POST", "OPTIONS"})
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Response
     */
    public function updateOwnerAction($id, Request $request)
    {
        if ($request->getMethod() === 'OPTIONS') {
            return new Response(
                null,
                204,
                $this->headers);
        }

        if ($request->getMethod() === 'POST') {
            $password = $request->get('_password');
            $oldPassword = $request->get('_old_password');
            $all = apache_request_headers();
            $header = $all['Authorization'];
            try {

                $tokenBearer = explode(' ', $header);
                $token = end($tokenBearer);
                $key = $this->get('service_container')->getParameter('secret');
                $credentials = JWT::decode($token, $key, array('HS256'));
                if (intval($credentials->sub) !== intval($id)) {
                    return new JsonResponse('Wrong Credentials', 401);
                }
                $em = $this->getDoctrine()->getManager();
                $adm = $em->getRepository('AdminBundle:User')->find($id);
                if (!empty($adm)) {
                    $doUpdate = false;
                    if (!empty($password) && !empty($oldPassword)) {
                        if ($this->checkPassword($adm, $oldPassword)) {
                            $adm->setPassword($password);
                            $secured = $this->getSecurePassword($adm);
                            $adm->setPassword($secured);
                            $doUpdate = true;
                        } else {
                            return new JsonResponse('', 401, $this->headers);
                        }

                    }

                    if ($doUpdate) {
                        $em->persist($adm);
                        $em->flush();
                        return new JsonResponse(
                            $id,
                            206,
                            array(
                                'Access-Control-Allow-Origin' => '*',
                                'Access-Control-Allow-Headers' => 'content-type',
                                'Access-Control-Allow-Methods' => 'POST,OPTIONS',
                                'Connection' => 'close'
                            )
                        );
                    } else {
                        return new JsonResponse(
                            $id,
                            204,
                            array(
                                'Access-Control-Allow-Origin' => '*',
                                'Access-Control-Allow-Headers' => 'content-type',
                                'Access-Control-Allow-Methods' => 'POST,OPTIONS',
                                'Connection' => 'close'
                            )
                        );
                    }

                }
                //wil return 404
                return new JsonResponse(
                    $id,
                    404,
                    array(
                        'Access-Control-Allow-Origin' => '*',
                        'Access-Control-Allow-Headers' => 'content-type',
                        'Access-Control-Allow-Methods' => 'POST,OPTIONS',
                        'Connection' => 'close'
                    )
                );

            } catch (Exception $exception) {
                return new JsonResponse(
                    $exception->getMessage(),
                    500,
                    $this->headers
                );
            }
        }

    }

    protected function getSecurePassword($entity)
    {
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        return $password;
    }

    protected function checkPassword($entity, $password)
    {
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $encodedPassword = $encoder->encodePassword($password, $entity->getSalt());
        return $encodedPassword === $entity->getPassword();

    }

    private function findPromos($class, $count)
    {
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
    private function sendSubscriberMail(Subscriber $entity)
    {
        $subject = $this->get('translator')->trans("front.subscriber.email.subject");
        $body = $this->renderView('FrontBundle:Email:subscriber.html.twig', array('entity' => $entity));

        $this->sendMail($body, $subject);
    }

    private function sendContactUsMail(ContactUs $entity)
    {
        $subject = $this->get('translator')->trans("front.contactus.email.subject");
        $body = $this->renderView('FrontBundle:Email:contactus.html.twig', array('entity' => $entity));

        $this->sendMail($body, $subject);
    }

    private function sendApplicantMail(Applicant $entity)
    {
        $subject = $this->get('translator')->trans("front.applicant.email.subject");
        $body = $this->renderView('FrontBundle:Email:applicant.html.twig', array('entity' => $entity));

        $this->sendMail($body, $subject);
    }

    private function sendMail($body, $subject)
    {
        $config = $this->get('config');
        $settings = $config->getData();

        $from = $settings->getAdminemail();
        $to = array($settings->getEmail());

        foreach ($to as $key => $v) {
            if (empty($v)) {
                unset($to[$key]);
            }
        }

        if (!empty($from) && count($to) > 0) {
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
