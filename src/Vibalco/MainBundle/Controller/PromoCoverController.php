<?php

namespace Vibalco\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\PromoCover;
use Vibalco\MainBundle\Form\PromoCoverType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * PromoCover controller.
 *
 * @Route("/admin/{_locale}/promo/cover" , defaults={"_locale" = "es"})
 */
class PromoCoverController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new PromoCover(), new PromoCoverType(), "MainBundle:PromoCover");
    }

    private function _datatable() {
        return parent::datatable();
    }

    /**
     * Lists all PromoCover entities.
     *
     * @Route("/", name="admin_promocover")
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_promocover_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all PromoCover entities.
     *
     * @Route("/list", name="admin_promocover_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a PromoCover entity.
     *
     * @Route("/{id}/show", name="admin_promocover_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new PromoCover entity.
     *
     * @Route("/new", name="admin_promocover_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new PromoCover entity.
     *
     * @Route("/create", name="admin_promocover_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        $message="Object created successfull";
        
        $entity = new $this->object;
        $form = $this->createForm(new $this->form, $entity);


        $form->bind($request);

        $result = array();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            try {
                $query = $em->createQuery("
                    SELECT MIN(p.showoffset)
                    FROM MainBundle:PromoCover p
                ");
                
                $offset = 0;
                try{
                    $offset = $query->getSingleScalarResult();
                }
                catch(\Exception $e){}
                
                $entity->setShowoffset($offset);
                
                $em->persist($entity);
                $em->flush();

                $result['success'] = true;
                $result['message'] = $message;
                $result['id'] = $entity->getId();
            } catch (\Exception $ex) {
                $result['success'] = false;
                $result['error'] = array('cause' => 'Intern', 'message' => $ex->getMessage());
            }
        }else {
            $result['success'] = false;            
            $result['error'] = array('cause' => 'Intern', 'message' => $this->get('formError')->generateMessage($form));
        }
        echo json_encode($result);
        die;
    }

    /**
     * Displays a form to edit an existing PromoCover entity.
     *
     * @Route("/{id}/edit", name="admin_promocover_edit")
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing PromoCover entity.
     *
     * @Route("/{id}", name="admin_promocover_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a PromoCover entity.
     *
     * @Route("/{id}", name="admin_promocover_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change PromoCover status
     *
     * @Route("/{id}/status", name="admin_promocover_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_promocover_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
