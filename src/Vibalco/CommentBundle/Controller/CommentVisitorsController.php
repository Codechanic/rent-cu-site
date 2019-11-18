<?php

namespace Vibalco\CommentBundle\Controller;;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\CommentBundle\Entity\CommentVisitors;
use Vibalco\CommentBundle\Form\CommentVisitorsType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
* CommentVisitors controller.
*
* @Route("/admin/{_locale}/commentvisitors" , defaults={"_locale" = "en"})
*/
class CommentVisitorsController extends AdminManager {

/**
* @return \Vibalco\DatatableBundle\Util\Datatable datatable
*/
function __construct() {
parent::__construct(new CommentVisitors(), new CommentVisitorsType(), "CommentBundle:CommentVisitors");
}

private function _datatable() {
       $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $qb->from($this->repository, 'p')->orderBy('p.id', 'desc');

        $datatable = $this->get('datatable')
                ->setFields(array(
                    'app.common.title' => 'p.client',                     
                    '_identifier_' => 'p.id',
                ))
                ->setSearch(true)
                ->setMultiple(
                array(
                    'delete' => array(
                        'title' => 'Delete',
                        'route' => 'admin_commentvisitors_delete_multiple' // path to multiple delete route
                    )
                )
                )
                    ->setColumns(array(
                    'app.common.title' => '1',                   
                    
                ))

        ;
        $datatable->getQueryBuilder()->setDoctrineQueryBuilder($qb);
        return $datatable;
}

/**
* Lists all CommentVisitors entities.
*
* @Route("/", name="admin_commentvisitors")
* @Template()
*/
public function indexAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* @Route("/grid", name="admin_commentvisitors_grid")
*/
public function gridAction() {
return $this->_datatable()->execute();
}

/**
* Lists all CommentVisitors entities.
*
* @Route("/list", name="admin_commentvisitors_list")
* @Template()
*/
public function listAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* Finds and displays a CommentVisitors entity.
*
* @Route("/{id}/show", name="admin_commentvisitors_show")
* @Template()
*/
public function showAction($id) {
return parent::showObject($id);
}

/**
* Displays a form to create a new CommentVisitors entity.
*
* @Route("/new", name="admin_commentvisitors_new")
* @Template()
*/
public function newAction() {
return parent::NewForm();
}

/**
* Creates a new CommentVisitors entity.
*
* @Route("/create", name="admin_commentvisitors_create")
* @Method("POST")
*/
public function createAction(Request $request) {
parent::createObject($request);
}

/**
* Displays a form to edit an existing CommentVisitors entity.
*
* @Route("/{id}/edit", name="admin_commentvisitors_edit")     *
* @Template()
*/
public function editAction($id) {
return parent::editObject($id);
}

/**
* Edits an existing CommentVisitors entity.
*
* @Route("/{id}", name="admin_commentvisitors_update")
* @Method("PUT")
*/
public function updateAction(Request $request, $id) {
return parent::updateObject($request, $id);
}

/**
* Deletes a CommentVisitors entity.
*
* @Route("/{id}", name="admin_commentvisitors_delete")
* @Method("DELETE")
*/
public function deleteAction(Request $request, $id) {
parent::deleteObject($request, $id);
}

/**
* Uncomment This if you have attribute enabled
* Change CommentVisitors status
*
* @Route("/{id}/status", name="admin_commentvisitors_status")
* @Method("POST")
*/
/*public function statusAction(Request $request, $id) {
return parent::statusObject($request, $id);
}*/


/**
* Deletes a Navegadores entity multiple.
*
* @Route("/delete_multiple", name="admin_commentvisitors_delete_multiple")
* @Method("POST")
*/
public function deletemultipleAction() {
$data = $this->getRequest()->get('dataTables');
$ids = $data['actions'];       
parent::delteObjects($this->getRequest(),$ids);
}

}

