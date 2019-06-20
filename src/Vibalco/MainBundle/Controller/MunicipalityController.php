<?php

namespace Vibalco\MainBundle\Controller;;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\Municipality;
use Vibalco\MainBundle\Form\MunicipalityType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
* Municipality controller.
*
* @Route("/admin/{_locale}/municipality" , defaults={"_locale" = "en"})
*/
class MunicipalityController extends AdminManager {

/**
* @return \Vibalco\DatatableBundle\Util\Datatable datatable
*/
function __construct() {
parent::__construct(new Municipality(), new MunicipalityType(), "MainBundle:Municipality");
}

private function _datatable() {
return parent::datatable();
}

/**
* Lists all Municipality entities.
*
* @Route("/", name="admin_municipality")
* @Template()
*/
public function indexAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* @Route("/grid", name="admin_municipality_grid")
*/
public function gridAction() {
return $this->_datatable()->execute();
}

/**
* Lists all Municipality entities.
*
* @Route("/list", name="admin_municipality_list")
* @Template()
*/
public function listAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* Finds and displays a Municipality entity.
*
* @Route("/{id}/show", name="admin_municipality_show")
* @Template()
*/
public function showAction($id) {
return parent::showObject($id);
}

/**
* Displays a form to create a new Municipality entity.
*
* @Route("/new", name="admin_municipality_new")
* @Template()
*/
public function newAction() {
return parent::NewForm();
}

/**
* Creates a new Municipality entity.
*
* @Route("/create", name="admin_municipality_create")
* @Method("POST")
*/
public function createAction(Request $request) {
parent::createObject($request);
}

/**
* Displays a form to edit an existing Municipality entity.
*
* @Route("/{id}/edit", name="admin_municipality_edit")     *
* @Template()
*/
public function editAction($id) {
return parent::editObject($id);
}

/**
* Edits an existing Municipality entity.
*
* @Route("/{id}", name="admin_municipality_update")
* @Method("PUT")
*/
public function updateAction(Request $request, $id) {
return parent::updateObject($request, $id);
}

/**
* Deletes a Municipality entity.
*
* @Route("/{id}", name="admin_municipality_delete")
* @Method("DELETE")
*/
public function deleteAction(Request $request, $id) {
parent::deleteObject($request, $id);
}

/**
* Uncomment This if you have attribute enabled
* Change Municipality status
*
* @Route("/{id}/status", name="admin_municipality_status")
* @Method("POST")
*/
/*public function statusAction(Request $request, $id) {
return parent::statusObject($request, $id);
}*/


/**
* Deletes a Navegadores entity multiple.
*
* @Route("/delete_multiple", name="admin_municipality_delete_multiple")
* @Method("POST")
*/
public function deletemultipleAction() {
$data = $this->getRequest()->get('dataTables');
$ids = $data['actions'];       
parent::delteObjects($this->getRequest(),$ids);
}

}

