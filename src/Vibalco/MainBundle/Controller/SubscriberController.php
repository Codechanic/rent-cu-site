<?php

namespace Vibalco\MainBundle\Controller;
;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\Subscriber;
use Vibalco\MainBundle\Form\SubscriberType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * Subscriber controller.
 *
 * @Route("/admin/{_locale}/subscriber" , defaults={"_locale" = "en"})
 */
class SubscriberController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new Subscriber(), new SubscriberType(), "MainBundle:Subscriber");
    }

    private function _datatable() {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $qb->from($this->repository, 'p')->orderBy('p.id', 'desc');

        $datatable = $this->get('datatable')
                ->setFields(array(
                    'app.common.title' => 'p.email',
                    'admin.subscriber.enabled' => 'p.enabled',
                    'admin.subscriber.locked' => 'p.locked',
                    '_identifier_' => 'p.id',
                ))
                ->setSearch(true)
                ->setMultiple(
                    array(
                        'delete' => array(
                            'title' => 'Delete',
                            'route' => 'admin_subscriber_delete_multiple' // path to multiple delete route
                        )
                    )
                )
                ->setColumns(array(
                    'app.common.title' => '1',
                ))

        ;
        $datatable->getQueryBuilder()->setDoctrineQueryBuilder($qb);
        return $datatable;
    }

    /**
     * Lists all Subscriber entities.
     *
     * @Route("/", name="admin_subscriber")
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_subscriber_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all Subscriber entities.
     *
     * @Route("/list", name="admin_subscriber_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a Subscriber entity.
     *
     * @Route("/{id}/show", name="admin_subscriber_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new Subscriber entity.
     *
     * @Route("/new", name="admin_subscriber_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new Subscriber entity.
     *
     * @Route("/create", name="admin_subscriber_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        parent::createObject($request);
    }

    /**
     * Displays a form to edit an existing Subscriber entity.
     *
     * @Route("/{id}/edit", name="admin_subscriber_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing Subscriber entity.
     *
     * @Route("/{id}", name="admin_subscriber_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a Subscriber entity.
     *
     * @Route("/{id}", name="admin_subscriber_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change Subscriber status
     *
     * @Route("/{id}/status", name="admin_subscriber_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_subscriber_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
