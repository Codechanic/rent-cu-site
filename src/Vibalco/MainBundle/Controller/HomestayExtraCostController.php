<?php

namespace Vibalco\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\HomestayExtraCost;
use Vibalco\MainBundle\Form\HomestayExtraCostType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * HomestayExtraCost controller.
 *
 * @Route("/admin/{_locale}/homestay/extracost" , defaults={"_locale" = "es"})
 */
class HomestayExtraCostController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new HomestayExtraCost(), new HomestayExtraCostType(), "MainBundle:HomestayExtraCost");
    }

    private function _datatable() {
        return parent::datatable();
    }

    /**
     * Lists all HomestayExtraCost entities.
     *
     * @Route("/", name="admin_homestayextracost")
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_homestayextracost_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all HomestayExtraCost entities.
     *
     * @Route("/list", name="admin_homestayextracost_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a HomestayExtraCost entity.
     *
     * @Route("/{id}/show", name="admin_homestayextracost_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new HomestayExtraCost entity.
     *
     * @Route("/new", name="admin_homestayextracost_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new HomestayExtraCost entity.
     *
     * @Route("/create", name="admin_homestayextracost_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        parent::createObject($request);
    }

    /**
     * Displays a form to edit an existing HomestayExtraCost entity.
     *
     * @Route("/{id}/edit", name="admin_homestayextracost_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing HomestayExtraCost entity.
     *
     * @Route("/{id}", name="admin_homestayextracost_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a HomestayExtraCost entity.
     *
     * @Route("/{id}", name="admin_homestayextracost_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change HomestayExtraCost status
     *
     * @Route("/{id}/status", name="admin_homestayextracost_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_homestayextracost_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
