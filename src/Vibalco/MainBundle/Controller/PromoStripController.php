<?php

namespace Vibalco\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\PromoStrip;
use Vibalco\MainBundle\Form\PromoStripType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * PromoStrip controller.
 *
 * @Route("/admin/{_locale}/promo/srip" , defaults={"_locale" = "es"})
 */
class PromoStripController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new PromoStrip(), new PromoStripType(), "MainBundle:PromoStrip");
    }

    private function _datatable() {
        return parent::datatable();
    }

    /**
     * Lists all PromoStrip entities.
     *
     * @Route("/", name="admin_promostrip")
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_promostrip_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all PromoStrip entities.
     *
     * @Route("/list", name="admin_promostrip_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a PromoStrip entity.
     *
     * @Route("/{id}/show", name="admin_promostrip_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new PromoStrip entity.
     *
     * @Route("/new", name="admin_promostrip_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new PromoStrip entity.
     *
     * @Route("/create", name="admin_promostrip_create")
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
                    FROM MainBundle:PromoStrip p
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
     * Displays a form to edit an existing PromoStrip entity.
     *
     * @Route("/{id}/edit", name="admin_promostrip_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing PromoStrip entity.
     *
     * @Route("/{id}", name="admin_promostrip_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a PromoStrip entity.
     *
     * @Route("/{id}", name="admin_promostrip_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change PromoStrip status
     *
     * @Route("/{id}/status", name="admin_promostrip_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_promostrip_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
