<?php

namespace Vibalco\ContenBundle\Controller;;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\ContenBundle\Entity\Category;
use Vibalco\ContenBundle\Form\CategoryType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
* Category controller.
*
* @Route("/admin/{_locale}/category" , defaults={"_locale" = "en"})
*/
class CategoryController extends AdminManager {

/**
* @return \Vibalco\DatatableBundle\Util\Datatable datatable
*/
function __construct() {
parent::__construct(new Category(), new CategoryType(), "ContenBundle:Category");
}

private function _datatable() {
return parent::datatable();
}

/**
* Lists all Category entities.
*
* @Route("/", name="admin_category")
* @Template()
*/
public function indexAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* @Route("/grid", name="admin_category_grid")
*/
public function gridAction() {
return $this->_datatable()->execute();
}

/**
* Lists all Category entities.
*
* @Route("/list", name="admin_category_list")
* @Template()
*/
public function listAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* Finds and displays a Category entity.
*
* @Route("/{id}/show", name="admin_category_show")
* @Template()
*/
public function showAction($id) {
return parent::showObject($id);
}

/**
* Displays a form to create a new Category entity.
*
* @Route("/new", name="admin_category_new")
* @Template()
*/
public function newAction() {
return parent::NewForm();
}

/**
* Creates a new Category entity.
*
* @Route("/create", name="admin_category_create")
* @Method("POST")
*/
public function createAction(Request $request) {
parent::createObject($request);
}

/**
* Displays a form to edit an existing Category entity.
*
* @Route("/{id}/edit", name="admin_category_edit")     *
* @Template()
*/
public function editAction($id) {
return parent::editObject($id);
}

/**
* Edits an existing Category entity.
*
* @Route("/{id}", name="admin_category_update")
* @Method("PUT")
*/
public function updateAction(Request $request, $id) {
return parent::updateObject($request, $id);
}

/**
* Deletes a Category entity.
*
* @Route("/{id}", name="admin_category_delete")
* @Method("DELETE")
*/
public function deleteAction(Request $request, $id) {
parent::deleteObject($request, $id);
}

/**
* Uncomment This if you have attribute enabled
* Change Category status
*
* @Route("/{id}/status", name="admin_category_status")
* @Method("POST")
*/
/*public function statusAction(Request $request, $id) {
return parent::statusObject($request, $id);
}*/


/**
* Deletes a Navegadores entity multiple.
*
* @Route("/delete_multiple", name="admin_category_delete_multiple")
* @Method("POST")
*/
public function deletemultipleAction() {
$data = $this->getRequest()->get('dataTables');
$ids = $data['actions'];       
parent::delteObjects($this->getRequest(),$ids);
}

}

