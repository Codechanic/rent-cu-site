<?php

namespace Vibalco\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\HomestayFreeService;
use Vibalco\MainBundle\Form\HomestayFreeServiceType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * HomestayFreeService controller.
 *
 * @Route("/admin/{_locale}/homestay/freeservice" , defaults={"_locale" = "es"})
 */
class HomestayFreeServiceController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new HomestayFreeService(), new HomestayFreeServiceType(), "MainBundle:HomestayFreeService");
    }

    private function _datatable() {
        return parent::datatable();
    }

    /**
     * Lists all HomestayFreeService entities.
     *
     * @Route("/", name="admin_homestayfreeservice")
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_homestayfreeservice_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all HomestayFreeService entities.
     *
     * @Route("/list", name="admin_homestayfreeservice_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a HomestayFreeService entity.
     *
     * @Route("/{id}/show", name="admin_homestayfreeservice_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new HomestayFreeService entity.
     *
     * @Route("/new", name="admin_homestayfreeservice_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new HomestayFreeService entity.
     *
     * @Route("/create", name="admin_homestayfreeservice_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        parent::createObject($request);
    }

    /**
     * Displays a form to edit an existing HomestayFreeService entity.
     *
     * @Route("/{id}/edit", name="admin_homestayfreeservice_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing HomestayFreeService entity.
     *
     * @Route("/{id}", name="admin_homestayfreeservice_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a HomestayFreeService entity.
     *
     * @Route("/{id}", name="admin_homestayfreeservice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change HomestayFreeService status
     *
     * @Route("/{id}/status", name="admin_homestayfreeservice_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_homestayfreeservice_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
