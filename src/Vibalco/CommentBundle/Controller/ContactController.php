<?php

namespace Vibalco\CommentBundle\Controller;;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\CommentBundle\Entity\Contact;
use Vibalco\CommentBundle\Form\ContactType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
* Contact controller.
*
* @Route("/{_locale}/admin/contact" , defaults={"_locale" = "en"})
*/
class ContactController extends AdminManager {

/**
* @return \Vibalco\DatatableBundle\Util\Datatable datatable
*/
function __construct() {
parent::__construct(new Contact(), new ContactType(), "CommentBundle:Contact");
}

private function _datatable() {
       $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        /*$qb->from($this->repository, 'p')->orderBy('p.id', 'desc');*/

        $datatable = $this->get('datatable')
                ->setEntity($this->repository, 'p')
                ->setFields(array(
                    'admin.common.title' => 'p.email',                     
                    '_identifier_' => 'p.id',
                ))
                ->setSearch(true)
                ->setMultiple(
                array(
                    'delete' => array(
                        'title' => 'admin.action.delete',
                        'route' => 'admin_contact_delete_multiple' // path to multiple delete route
                    )
                )
                )
                    ->setColumns(array(
                    'app.common.title' => '1',                   
                    
                ))

        ;
        
        return $datatable;
}

/**
* Lists all Contact entities.
*
* @Route("/", name="admin_contact")
* @Template()
*/
public function indexAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* @Route("/grid", name="admin_contact_grid")
*/
public function gridAction() {
return $this->_datatable()->execute();
}

/**
* Lists all Contact entities.
*
* @Route("/list", name="admin_contact_list")
* @Template()
*/
public function listAction() {
$this->_datatable();
return array('data'=>$this->_datatable()->getColumns());
}

/**
* Finds and displays a Contact entity.
*
* @Route("/{id}/show", name="admin_contact_show")
* @Template()
*/
public function showAction($id) {
return parent::showObject($id);
}

/**
* Displays a form to create a new Contact entity.
*
* @Route("/new", name="admin_contact_new")
* @Template()
*/
public function newAction() {
return parent::NewForm();
}

/**
* Creates a new Contact entity.
*
* @Route("/create", name="admin_contact_create")
* @Method("POST")
*/
public function createAction(Request $request) {
        $entity = new Contact();
        $form = $this->createForm(new ContactType(), $entity);
        $form->bind($request);


        $result = array();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            try {
                $token = md5(uniqid(rand(), true));
                $entity->setToken($token);
                $entity->setEnabled(true);

                $em->persist($entity);
                $em->flush();

                $result['success'] = true;
                $result['message'] = $this->get('translator')->trans('news.contact.successfull.create');
                $result['id'] = $entity->getId();
            } catch (\Exception $ex) {
                $result['success'] = false;
                $result['error'] = array('cause' => 'Intern', 'message' => $ex->getMessage());
            }
        } else {
            $result['success'] = false;
            $result['error'] = array('cause' => 'Intern', 'message' => $this->get('formError')->generateMessage($form));
        }

        echo json_encode($result);
        die;
    
}

/**
* Displays a form to edit an existing Contact entity.
*
* @Route("/{id}/edit", name="admin_contact_edit")     *
* @Template()
*/
public function editAction($id) {
return parent::editObject($id);
}

/**
* Edits an existing Contact entity.
*
* @Route("/{id}", name="admin_contact_update")
* @Method("PUT")
*/
public function updateAction(Request $request, $id) {
return parent::updateObject($request, $id);
}

/**
* Deletes a Contact entity.
*
* @Route("/{id}", name="admin_contact_delete")
* @Method("DELETE")
*/
public function deleteAction(Request $request, $id) {
parent::deleteObject($request, $id);
}

/**
* Uncomment This if you have attribute enabled
* Change Contact status
*
* @Route("/{id}/status", name="admin_contact_status")
* @Method("POST")
*/
/*public function statusAction(Request $request, $id) {
return parent::statusObject($request, $id);
}*/


/**
* Deletes a Navegadores entity multiple.
*
* @Route("/delete_multiple", name="admin_contact_delete_multiple")
* @Method("POST")
*/
public function deletemultipleAction() {
$data = $this->getRequest()->get('dataTables');
$ids = $data['actions'];       
parent::delteObjects($this->getRequest(),$ids);
}

}

