<?php

namespace Vibalco\MainBundle\Controller;
;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\AcommodationType;
use Vibalco\MainBundle\Form\AcommodationTypeType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * AcommodationType controller.
 *
 * @Route("/admin/{_locale}/acommodationtype" , defaults={"_locale" = "%locale%"})
 */
class AcommodationTypeController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new AcommodationType(), new AcommodationTypeType(), "MainBundle:AcommodationType");
    }

    private function _datatable() {
        return parent::datatable();
    }

    /**
     * Lists all AcommodationType entities.
     *
     * @Route("/", name="admin_acommodationtype")
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_acommodationtype_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all AcommodationType entities.
     *
     * @Route("/list", name="admin_acommodationtype_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a AcommodationType entity.
     *
     * @Route("/{id}/show", name="admin_acommodationtype_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new AcommodationType entity.
     *
     * @Route("/new", name="admin_acommodationtype_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new AcommodationType entity.
     *
     * @Route("/create", name="admin_acommodationtype_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        parent::createObject($request);
    }

    /**
     * Displays a form to edit an existing AcommodationType entity.
     *
     * @Route("/{id}/edit", name="admin_acommodationtype_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing AcommodationType entity.
     *
     * @Route("/{id}", name="admin_acommodationtype_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a AcommodationType entity.
     *
     * @Route("/{id}", name="admin_acommodationtype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change AcommodationType status
     *
     * @Route("/{id}/status", name="admin_acommodationtype_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_acommodationtype_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
