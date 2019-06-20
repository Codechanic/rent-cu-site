<?php

namespace Vibalco\SliderBundle\Controller;;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\SliderBundle\Entity\Slide;
use Vibalco\SliderBundle\Form\SlideType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
* Slide controller.
*
* @Route("/admin/{_locale}/slide" , defaults={"_locale" = "en"})
*/
class SlideController extends AdminManager {

/**
* @return \Vibalco\DatatableBundle\Util\Datatable datatable
*/
function __construct() {
parent::__construct(new Slide(), new SlideType(), "SliderBundle:Slide");
}

private function _datatable() {
$qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $qb->from($this->repository, 'p')->orderBy('p.id', 'desc');

        $datatable = $this->get('datatable')
                ->setFields(array(
                    'admin.common.title' => 'p.name',
                    '_identifier_' => 'p.id',
                ))
                ->setSearch(true);

        $datatable->getQueryBuilder()->setDoctrineQueryBuilder($qb);
        return $datatable;
}

/**
* Lists all Slide entities.
*
* @Route("/", name="admin_slide")
* @Template()
*/
public function indexAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* @Route("/grid", name="admin_slide_grid")
*/
public function gridAction() {
return $this->_datatable()->execute();
}

/**
* Lists all Slide entities.
*
* @Route("/list", name="admin_slide_list")
* @Template()
*/
public function listAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* Finds and displays a Slide entity.
*
* @Route("/{id}/show", name="admin_slide_show")
* @Template()
*/
public function showAction($id) {
return parent::showObject($id);
}

/**
* Displays a form to create a new Slide entity.
*
* @Route("/new", name="admin_slide_new")
* @Template()
*/
public function newAction() {
return parent::NewForm();
}

/**
* Creates a new Slide entity.
*
* @Route("/create", name="admin_slide_create")
* @Method("POST")
*/
public function createAction(Request $request) {
parent::createObject($request);
}

/**
* Displays a form to edit an existing Slide entity.
*
* @Route("/{id}/edit", name="admin_slide_edit")     *
* @Template()
*/
public function editAction($id) {
return parent::editObject($id);
}

/**
* Edits an existing Slide entity.
*
* @Route("/{id}", name="admin_slide_update")
* @Method("PUT")
*/
public function updateAction(Request $request, $id) {
return parent::updateObject($request, $id);
}

/**
* Deletes a Slide entity.
*
* @Route("/{id}", name="admin_slide_delete")
* @Method("POST")
*/
public function deleteAction(Request $request, $id) {
parent::deleteObject($request, $id);
}

/**
* Uncomment This if you have attribute enabled
* Change Slide status
*
* @Route("/{id}/status", name="admin_slide_status")
* @Method("POST")
*/
/*public function statusAction(Request $request, $id) {
return parent::statusObject($request, $id);
}*/


/**
* Deletes a Navegadores entity multiple.
*
* @Route("/delete_multiple", name="admin_slide_delete_multiple")
* @Method("POST")
*/
public function deletemultipleAction() {
$data = $this->getRequest()->get('dataTables');
$ids = $data['actions'];       
parent::delteObjects($this->getRequest(),$ids);
}

}

