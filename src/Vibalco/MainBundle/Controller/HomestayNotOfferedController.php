<?php

namespace Vibalco\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\HomestayNotOffered;
use Vibalco\MainBundle\Form\HomestayNotOfferedType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * HomestayNotOffered controller.
 *
 * @Route("/admin/{_locale}/homestay/notoffered" , defaults={"_locale" = "es"})
 */
class HomestayNotOfferedController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new HomestayNotOffered(), new HomestayNotOfferedType(), "MainBundle:HomestayNotOffered");
    }

    private function _datatable() {
        return parent::datatable();
    }

    /**
     * Lists all HomestayNotOffered entities.
     *
     * @Route("/", name="admin_homestaynotoffered")
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_homestaynotoffered_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all HomestayNotOffered entities.
     *
     * @Route("/list", name="admin_homestaynotoffered_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a HomestayNotOffered entity.
     *
     * @Route("/{id}/show", name="admin_homestaynotoffered_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new HomestayNotOffered entity.
     *
     * @Route("/new", name="admin_homestaynotoffered_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new HomestayNotOffered entity.
     *
     * @Route("/create", name="admin_homestaynotoffered_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        parent::createObject($request);
    }

    /**
     * Displays a form to edit an existing HomestayNotOffered entity.
     *
     * @Route("/{id}/edit", name="admin_homestaynotoffered_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing HomestayNotOffered entity.
     *
     * @Route("/{id}", name="admin_homestaynotoffered_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a HomestayNotOffered entity.
     *
     * @Route("/{id}", name="admin_homestaynotoffered_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change HomestayNotOffered status
     *
     * @Route("/{id}/status", name="admin_homestaynotoffered_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_homestaynotoffered_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
