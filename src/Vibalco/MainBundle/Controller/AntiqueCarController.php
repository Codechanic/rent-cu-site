<?php

namespace Vibalco\MainBundle\Controller;;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\AntiqueCar;
use Vibalco\MainBundle\Form\AntiqueCarType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
* AntiqueCar controller.
*
* @Route("/admin/{_locale}/antiquecar" , defaults={"_locale" = "es"})
*/
class AntiqueCarController extends AdminManager {

/**
* @return \Vibalco\DatatableBundle\Util\Datatable datatable
*/
function __construct() {
parent::__construct(new AntiqueCar(), new AntiqueCarType(), "MainBundle:AntiqueCar");
}

private function _datatable() {
return parent::datatable();
}

/**
* Lists all AntiqueCar entities.
*
* @Route("/", name="admin_antiquecar")
* @Template()
*/
public function indexAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* @Route("/grid", name="admin_antiquecar_grid")
*/
public function gridAction() {
return $this->_datatable()->execute();
}

/**
* Lists all AntiqueCar entities.
*
* @Route("/list", name="admin_antiquecar_list")
* @Template()
*/
public function listAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* Finds and displays a AntiqueCar entity.
*
* @Route("/{id}/show", name="admin_antiquecar_show")
* @Template()
*/
public function showAction($id) {
return parent::showObject($id);
}

/**
* Displays a form to create a new AntiqueCar entity.
*
* @Route("/new", name="admin_antiquecar_new")
* @Template()
*/
public function newAction() {
return parent::NewForm();
}

/**
* Creates a new AntiqueCar entity.
*
* @Route("/create", name="admin_antiquecar_create")
* @Method("POST")
*/
public function createAction(Request $request) {
parent::createObject($request);
}

/**
* Displays a form to edit an existing AntiqueCar entity.
*
* @Route("/{id}/edit", name="admin_antiquecar_edit")     *
* @Template()
*/
public function editAction($id) {
return parent::editObject($id);
}

/**
* Edits an existing AntiqueCar entity.
*
* @Route("/{id}", name="admin_antiquecar_update")
* @Method("PUT")
*/
public function updateAction(Request $request, $id) {
return parent::updateObject($request, $id);
}

/**
* Deletes a AntiqueCar entity.
*
* @Route("/{id}", name="admin_antiquecar_delete")
* @Method("DELETE")
*/
public function deleteAction(Request $request, $id) {
parent::deleteObject($request, $id);
}

/**
* Uncomment This if you have attribute enabled
* Change AntiqueCar status
*
* @Route("/{id}/status", name="admin_antiquecar_status")
* @Method("POST")
*/
/*public function statusAction(Request $request, $id) {
return parent::statusObject($request, $id);
}*/


/**
* Deletes a Navegadores entity multiple.
*
* @Route("/delete_multiple", name="admin_antiquecar_delete_multiple")
* @Method("POST")
*/
public function deletemultipleAction() {
$data = $this->getRequest()->get('dataTables');
$ids = $data['actions'];       
parent::delteObjects($this->getRequest(),$ids);
}

}

