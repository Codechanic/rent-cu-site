<?php

namespace Vibalco\CommentBundle\Controller;

;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\CommentBundle\Entity\Mailing;
use Vibalco\CommentBundle\Form\MailingType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * Mailing controller.
 *
 * @Route("/{_locale}/admin/mailing" , defaults={"_locale" = "en"})
 */
class MailingController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new Mailing(), new MailingType(), "CommentBundle:Mailing");
    }

    private function _datatable() {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();



        $datatable = $this->get('datatable')
                ->setEntity($this->repository, 'p')
                ->setFields(array(
                    'admin.common.title' => 'p.title',
                    'Enviado' => 'p.sended',
                    '_identifier_' => 'p.id',
                ))
                ->setSearch(true)
                ->setMultiple(
                        array(
                            'delete' => array(
                                'title' => 'admin.action.delete',
                                'route' => 'admin_mailing_delete_multiple' // path to multiple delete route
                            ),
                            'send' => array(
                                'title' => 'admin.action.send',
                                'route' => 'admin_mailing_send_multiple' // path to multiple delete route
                            )
                        )
                )
                ->setColumns(array(
            'app.common.title' => '1',
                ))

        ;

        return $datatable;
    }

    /**
     * Lists all Mailing entities.
     *
     * @Route("/", name="admin_mailing")
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_mailing_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all Mailing entities.
     *
     * @Route("/list", name="admin_mailing_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a Mailing entity.
     *
     * @Route("/{id}/show", name="admin_mailing_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new Mailing entity.
     *
     * @Route("/new", name="admin_mailing_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new Mailing entity.
     *
     * @Route("/create", name="admin_mailing_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        $entity = new $this->object;
        $form = $this->createForm(new $this->form, $entity);

        $message = "Saved Succesfully";
        $form->bind($request);

        $result = array();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try {
                $entity->setSended(false);
                $em->persist($entity);
                $em->flush();

                $result['success'] = true;
                $result['message'] = $message;
                $result['id'] = $entity->getId();
            } catch (\Exception $ex) {
                $result['success'] = false;
                $result['error'] = array('cause' => 'Intern', 'message' => $ex->getMessage());
            }
        } else {
            $result['success'] = false;
            $result['error'] = array('cause' => 'Intern', 'message' => $this->get('formError')->generateMessage($form));
        }
        echo json_encode($result);
        die;
    }

    /**
     * Displays a form to edit an existing Mailing entity.
     *
     * @Route("/{id}/edit", name="admin_mailing_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing Mailing entity.
     *
     * @Route("/{id}", name="admin_mailing_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a Mailing entity.
     *
     * @Route("/{id}", name="admin_mailing_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change Mailing status
     *
     * @Route("/{id}/status", name="admin_mailing_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_mailing_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/send_multiple", name="admin_mailing_send_multiple")
     * @Method("POST")
     */
    public function sendmultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        foreach ($ids as $id) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository($this->repository)->find($id);
            if ($entity) {
                $entity->setSended(true);
                
                foreach ($entity->getContacts() as $contact) {
                    $message = \Swift_Message::newInstance()
                            ->setSubject($entity->getTitle())
                            ->setFrom(array($this->get('service_container')->getParameter('mailer_sender_email') => $this->get('service_container')->getParameter('mailer_sender_name')))
                            ->setTo($contact->getEmail())
                  
                            ->setBody(
                            $this->renderView(
                                    'CommentBundle:Default:mailtemplate.html.twig', array('entity' => $entity, 'contact' => $contact,"domain" => $this->get("appsettings")->getSetting()->getDomain())
                            ), 'text/html'
                            )
                    ;
                    $this->get('mailer')->send($message);
                }
                $em->persist($entity);
                $em->flush();
                $message = $this->get('translator')->trans('admin.send');
                $result['success'] = true;
                $result['message'] = $message;
                $result['id'] = $entity->getId();
            }
        }
        echo json_encode($result);
        die;
    }

}
