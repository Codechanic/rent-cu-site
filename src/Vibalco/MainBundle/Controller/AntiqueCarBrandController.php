<?php

namespace Vibalco\MainBundle\Controller;;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\AntiqueCarBrand;
use Vibalco\MainBundle\Form\AntiqueCarBrandType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
* AntiqueCarBrand controller.
*
* @Route("/admin/{_locale}/antiquecarbrand" , defaults={"_locale" = "es"})
*/
class AntiqueCarBrandController extends AdminManager {

/**
* @return \Vibalco\DatatableBundle\Util\Datatable datatable
*/
function __construct() {
parent::__construct(new AntiqueCarBrand(), new AntiqueCarBrandType(), "MainBundle:AntiqueCarBrand");
}

private function _datatable() {
return parent::datatable();
}

/**
* Lists all AntiqueCarBrand entities.
*
* @Route("/", name="admin_antiquecarbrand")
* @Template()
*/
public function indexAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* @Route("/grid", name="admin_antiquecarbrand_grid")
*/
public function gridAction() {
return $this->_datatable()->execute();
}

/**
* Lists all AntiqueCarBrand entities.
*
* @Route("/list", name="admin_antiquecarbrand_list")
* @Template()
*/
public function listAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* Finds and displays a AntiqueCarBrand entity.
*
* @Route("/{id}/show", name="admin_antiquecarbrand_show")
* @Template()
*/
public function showAction($id) {
return parent::showObject($id);
}

/**
* Displays a form to create a new AntiqueCarBrand entity.
*
* @Route("/new", name="admin_antiquecarbrand_new")
* @Template()
*/
public function newAction() {
return parent::NewForm();
}

/**
* Creates a new AntiqueCarBrand entity.
*
* @Route("/create", name="admin_antiquecarbrand_create")
* @Method("POST")
*/
public function createAction(Request $request) {
parent::createObject($request);
}

/**
* Displays a form to edit an existing AntiqueCarBrand entity.
*
* @Route("/{id}/edit", name="admin_antiquecarbrand_edit")     *
* @Template()
*/
public function editAction($id) {
return parent::editObject($id);
}

/**
* Edits an existing AntiqueCarBrand entity.
*
* @Route("/{id}", name="admin_antiquecarbrand_update")
* @Method("PUT")
*/
public function updateAction(Request $request, $id) {
return parent::updateObject($request, $id);
}

/**
* Deletes a AntiqueCarBrand entity.
*
* @Route("/{id}", name="admin_antiquecarbrand_delete")
* @Method("DELETE")
*/
public function deleteAction(Request $request, $id) {
parent::deleteObject($request, $id);
}

/**
* Uncomment This if you have attribute enabled
* Change AntiqueCarBrand status
*
* @Route("/{id}/status", name="admin_antiquecarbrand_status")
* @Method("POST")
*/
/*public function statusAction(Request $request, $id) {
return parent::statusObject($request, $id);
}*/


/**
* Deletes a Navegadores entity multiple.
*
* @Route("/delete_multiple", name="admin_antiquecarbrand_delete_multiple")
* @Method("POST")
*/
public function deletemultipleAction() {
$data = $this->getRequest()->get('dataTables');
$ids = $data['actions'];       
parent::delteObjects($this->getRequest(),$ids);
}

}

