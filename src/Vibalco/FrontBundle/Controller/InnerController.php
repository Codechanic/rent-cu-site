<?php

namespace Vibalco\FrontBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Vibalco\MainBundle\Entity\ContactForm;
use Vibalco\MainBundle\Form\ContactFormType;

/**
 * @Route("/{_locale}", defaults={"_locale" = "en"}, requirements={"_locale" = "|en|es"})
 */
class InnerController extends Controller
{
    /**
     *
     * @Route("/homestays", name="homestays")
     * @Template()
     */
    public function homestaysAction()
    {
        $em = $this->getDoctrine()->getManager();
        $fm = $this->get('homestay.filter');

        $rep = $em->getRepository('MainBundle:Homestay');

        $paginator = $this->get('ideup.simple_paginator');
        $paginator->setItemsPerPage(12);
        $paginator->setMaxPagerItems(10);

        $entities = $paginator->paginate($rep->queryFilter($fm->getFilter(), $fm->getOrder()))
            ->getResult();

        return array('entities' => $entities, 'paginator' => $paginator);
    }

    /**
     *
     * @Route("/homestayslider/{id}", name="homestayslider")
     * @Template()
     */
    public function homestaysliderAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Homestay')->find($id);

        if ($entity) {
            $images = $em->getRepository('GalleryBundle:Image')
                ->findBy(array('owner' => $entity->galleryOwner()));
        }

        if (!isset($images))
            $images = new ArrayCollection();

        return array('images' => $images, 'entity' => $entity);
    }

    /**
     *
     * @Route("/homestay/{slug}", name="homestay")
     * @Template()
     */
    public function homestayAction($slug)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Homestay')->findOneBy(array('slug' => $slug));

        if ($entity) {
            $this->registerVisit($entity);
        }

        return array('entity' => $entity);
    }

    /**
     *
     * @Route("/preview/{slug}", name="preview")
     * @Template()
     */
    public function previewAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Homestay')->findOneBy(array('slug' => $slug));

        return array('entity' => $entity);
    }

    /**
     *
     * @Route("/homestayfilter/add", name="homestayfilter_add")
     */
    public function homestayfilterAddAction(Request $request)
    {
        $this->changeFilter($request, 'homestayfilter');
        return $this->redirect($this->generateUrl('homestays'));
    }

    /**
     *
     * @Route("/homestayfilter/remove", name="homestayfilter_remove")
     */
    public function homestayfilterRemoveAction(Request $request)
    {
        $this->removeFilter($request, 'homestayfilter');
        return $this->redirect($this->generateUrl('homestays'));
    }

    /**
     * Change a set of values in the current filter
     */
    private function changeFilter(Request $request, $filtername)
    {

        $data = $request->get('data', array());
        $key = $request->get('key', null);
        $value = $request->get('value', null);

        if (!in_array($key, $data)) {
            $data[$key] = $value;
        }

        foreach ($data as $key => $value) {
            if ($value == null || $value == '')
                unset($data[$key]);
        }

        if (count($data) > 0) {
            $fm = $this->getFilterManager($filtername);

            $fm->setArray($data);
            $fm->flush();
        }
    }

    /**
     * Remove a filter value in the current filter
     */
    public function removeFilter(Request $request, $filtername)
    {

        $key = $request->get('key', null);
        $value = $request->get('value', null);

        if ($key != null) {
            $fm = $this->getFilterManager($filtername);

            $fm->remove($key, $value);
            $fm->flush();
        }
    }

    /**
     *
     * @Route("/homestayfilter", name="filter")
     * @Template()
     */
    public function homestayfilterAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('MainBundle:Homestay');

        $fm = $this->get('homestay.filter');

        return array('fm' => $fm);
    }

    /**
     * @Route("/reviews/{slug}", name="homestay_review")
     * @Template()
     */
    public function reviewsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Homestay')->findOneBy(array('slug' => $slug));

        $comments = $em->getRepository('FrontBundle:Comment')
            ->findBy(
                array('homestay' => $entity, 'enabled' => true)
            );

        if ($entity) {
            $this->registerVisit($entity);
        }

        return array('entity' => $entity, 'comments' => $comments);

    }

    /**
     * Test action TODO delete
     *
     * @Route("/test", name="test")
     * @Template()
     */
    public function testAction()
    {
        return array();
    }

    /**
     * Finds the filter manager corresponding to a given filtername
     */
    private function getFilterManager($filtername)
    {
        switch ($filtername) {
            case 'homestayfilter':
                return $this->get('homestay.filter');
            case 'antiquecarfilter':
                return $this->get('antiquecar.filter');
        }

        return null;
    }

    private function registerVisit($entity)
    {
        $request = $this->getRequest();
        $service = $this->get('visit');

        $ip = $request->getClientIp();
        $url = $request->getPathInfo();

        $service->registerVisit($entity, $ip, $url);
    }

    /**
     * @Route("/homestay/{slug}/contactform", name="homestay_contactform")
     * @Template
     */
    public function homestay_contactformAction(Request $request, $slug)
    {
        $form = $this->createForm(new ContactFormType(), new ContactForm());
        $msg = array('success' => '', 'error' => '');

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $entity = $form->getData();

                $homestay = $em->getRepository('MainBundle:Homestay')
                    ->findOneBy(array('slug' => $slug));

                $entity->setHomestay($homestay);

                try {
                    $em->persist($entity);
                    $em->flush();

                    //Send notification mail to site admins
                    $this->sendContactFormMail($entity);

                    $msg['success'] = 'front.contactform.message.success';
                    $form = $this->createForm(new ContactFormType(), new ContactForm());
                } catch (\Exception $e) {
                    $msg['error'] = 'front.contactform.message.internalerror';
                }
            } else {
                $msg['error'] = 'front.contactform.message.error';
            }
        }

        return array('form' => $form->createView(), 'msg' => $msg, 'slug' => $slug);
    }

    //TODO add i10n for subject and body content in emails
    private function sendContactFormMail(ContactForm $entity)
    {
        $subject = $this->get('translator')->trans("front.contactform.email.subject");

        $body = $this->renderView('FrontBundle:Email:contactform.html.twig', array('entity' => $entity));
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

    ///////////////////////// Acient car filter and actions ////////////////////

    /**
     *
     * @Route("/antiquecars", name="antiquecars")
     * @Template()
     */
    public function antiquecarsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $fm = $this->get('antiquecar.filter');

        $rep = $em->getRepository('MainBundle:AntiqueCar');

        $paginator = $this->get('ideup.simple_paginator');
        $paginator->setItemsPerPage(8);
        $paginator->setMaxPagerItems(10);

        $entities = $paginator->paginate($rep->queryFilter($fm->getFilter(), $fm->getOrder()))
            ->getResult();

        return array('entities' => $entities, 'paginator' => $paginator);
    }

    /**
     *
     * @Route("/antiquecarfilter/add", name="antiquecarfilter_add")
     */
    public function antiquecarfilterAddAction(Request $request)
    {
        $this->changeFilter($request, 'antiquecarfilter');
        return $this->redirect($this->generateUrl('antiquecars'));
    }

    /**
     *
     * @Route("/antiquecarfilter/remove", name="antiquecarfilter_remove")
     */
    public function antiquecarfilterRemoveAction(Request $request)
    {
        $this->removeFilter($request, 'antiquecarfilter');
        return $this->redirect($this->generateUrl('antiquecars'));
    }

    /**
     *
     * @Route("/antiquecarfilter", name="filter")
     * @Template()
     */
    public function antiquecarfilterAction()
    {
        $fm = $this->get('antiquecar.filter');

        return array('fm' => $fm);
    }

    /**
     * @Route("/homestay/{slug}/comment", name="homestay_comment")
     * @Template
     */
    public function commentAction($slug)
    {

        $em = $this->getDoctrine()->getManager();
        $homestay = $em->getRepository('MainBundle:Homestay')
            ->findOneBy(array('slug' => $slug));
        $comments = $em->getRepository('FrontBundle:Comment')
            ->findBy(
                array('homestay' => $homestay, 'enabled' => true),
                array()
            );
        $amount = count($comments);
        $slice = $comments;
        if ($amount > 5) {
            $slice = array_slice($comments, 0, 5);
        }

        return array(
            'comments' => $slice,
            'homestay' => $homestay,
            'amount' => $amount
        );
    }

    /**
     * @Route("/homestay/{slug}/comments/view", name="homestay_comment_view")
     * @Template
     */
    public function commentViewAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $homestay = $em->getRepository('MainBundle:Homestay')
            ->findOneBy(array('slug' => $slug, 'enabled' => true));

        $commentQuery = $em->getRepository('FrontBundle:Comment')
            ->getCommentByHomestayQuery($homestay);

        $paginator = $this->get('ideup.simple_paginator');
        $paginator->setItemsPerPage(7);
        $paginator->setMaxPagerItems(5);

        $entities = $paginator->paginate($commentQuery)
            ->getResult();

        return array(
            'comments' => $entities,
            'paginator' => $paginator,
            'slug' => $homestay->getSlug()
        );
    }

    /**
     *
     * @Route("/currency_exchange", name="currency_exchange")
     * @Template()
     */
    public function currencyExchangeAction()
    {
        $currencyExchangeService = $this->get('currencyexchange');

        try {
            $ce = $currencyExchangeService->getLatestCurrency();
            if (!empty($ce)) {
                $rates = json_decode($ce->getRates(), true);
                if ($rates) {
                    $allowed = array(
                        array(
                            'currency' => 'USD',
                            'amount' => $rates['USD']
                        ),
                        array(
                            'currency' => 'EUR',
                            'amount' => $rates['EUR']
                        )
                    );
                    return array(
                        'base' => $ce->getBase(),
                        'rates' => $allowed,
                        'updated' => $ce->getRetrievedAt(),
                        'success' => true
                    );
                }
                return array(
                    'success' => false,
                    'header' => 'front.applicant.currency_failed'
                );

            } else {
                return array(
                    'success' => false,
                    'header' => 'front.applicant.currency_failed'
                );
            }
        } catch (\Exception $exception) {
            return array();
        }
    }
}
