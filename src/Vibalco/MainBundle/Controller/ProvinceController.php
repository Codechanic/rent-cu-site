<?php

namespace Vibalco\MainBundle\Controller;;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\Province;
use Vibalco\MainBundle\Form\ProvinceType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
* Province controller.
*
* @Route("/admin/{_locale}/province" , defaults={"_locale" = "en"})
*/
class ProvinceController extends AdminManager {

/**
* @return \Vibalco\DatatableBundle\Util\Datatable datatable
*/
function __construct() {
parent::__construct(new Province(), new ProvinceType(), "MainBundle:Province");
}

private function _datatable() {
return parent::datatable();
}

/**
* Lists all Province entities.
*
* @Route("/", name="admin_province")
* @Template()
*/
public function indexAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* @Route("/grid", name="admin_province_grid")
*/
public function gridAction() {
return $this->_datatable()->execute();
}

/**
* Lists all Province entities.
*
* @Route("/list", name="admin_province_list")
* @Template()
*/
public function listAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* Finds and displays a Province entity.
*
* @Route("/{id}/show", name="admin_province_show")
* @Template()
*/
public function showAction($id) {
return parent::showObject($id);
}

/**
* Displays a form to create a new Province entity.
*
* @Route("/new", name="admin_province_new")
* @Template()
*/
public function newAction() {
return parent::NewForm();
}

/**
* Creates a new Province entity.
*
* @Route("/create", name="admin_province_create")
* @Method("POST")
*/
public function createAction(Request $request) {
parent::createObject($request);
}

/**
* Displays a form to edit an existing Province entity.
*
* @Route("/{id}/edit", name="admin_province_edit")     *
* @Template()
*/
public function editAction($id) {
return parent::editObject($id);
}

/**
* Edits an existing Province entity.
*
* @Route("/{id}", name="admin_province_update")
* @Method("PUT")
*/
public function updateAction(Request $request, $id) {
return parent::updateObject($request, $id);
}

/**
* Deletes a Province entity.
*
* @Route("/{id}", name="admin_province_delete")
* @Method("DELETE")
*/
public function deleteAction(Request $request, $id) {
parent::deleteObject($request, $id);
}

/**
* Uncomment This if you have attribute enabled
* Change Province status
*
* @Route("/{id}/status", name="admin_province_status")
* @Method("POST")
*/
/*public function statusAction(Request $request, $id) {
return parent::statusObject($request, $id);
}*/


/**
* Deletes a Navegadores entity multiple.
*
* @Route("/delete_multiple", name="admin_province_delete_multiple")
* @Method("POST")
*/
public function deletemultipleAction() {
$data = $this->getRequest()->get('dataTables');
$ids = $data['actions'];       
parent::delteObjects($this->getRequest(),$ids);
}

}

