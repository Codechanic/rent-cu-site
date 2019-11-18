<?php

namespace Vibalco\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\Homestay;
use Vibalco\MainBundle\Form\HomestayType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * Homestay controller.
 *
 * @Route("/admin/{_locale}/homestay" , defaults={"_locale" = "es"})
 */
class HomestayController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new Homestay(), new HomestayType(), "MainBundle:Homestay");
    }

    private function _datatable() 
    {   
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $qb->from($this->repository, 'p')->orderBy('p.id', 'desc');
        $qb->leftJoin('p.municipality', 'm');
        $qb->leftJoin('p.acommodation', 'a');

        $datatable = $this->get('datatable')
                ->setFields(array(
                    'Título' => 'p.name',
                    'Municipio' => 'm.name',
                    'Alojamiento' => 'a.name',
                    '_identifier_' => 'p.id',
                ))
                ->setSearch(true)
        ;
        $datatable->getQueryBuilder()->setDoctrineQueryBuilder($qb);
        return $datatable;
    }
    
    /**
     * @Route("/filter", name="admin_homestay_filter")
     * @Template
     */
    public function filterAction() {
        
        $em= $this->getDoctrine()->getManager();

        $municipalities = $em->getRepository('MainBundle:Municipality')->findAll();
        $acommodations = $em->getRepository('MainBundle:AcommodationType')->findAll();
        
        return array(
            'municipalities' => $municipalities,
            'acommodations' => $acommodations
        );
    }

    /**
     * Lists all Homestay entities.
     *
     * @Route("/", name="admin_homestay")
     * @Route("/selected/{id}", requirements={ "id" = "\d+"}, name="admin_homestay_id")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($id = null) {
        $this->_datatable();
        return array(
            'data' => $this->_datatable()->getColumns(),
            'id' => $id
        );
    }

    /**
     * @Route("/grid", name="admin_homestay_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all Homestay entities.
     *
     * @Route("/list", name="admin_homestay_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a Homestay entity.
     *
     * @Route("/{id}/show", name="admin_homestay_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new Homestay entity.
     *
     * @Route("/new", name="admin_homestay_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new Homestay entity.
     *
     * @Route("/create", name="admin_homestay_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
//parent::createObject($request);
        $message = "Object created successfull";

        $entity = new $this->object;
        $form = $this->createForm(new $this->form, $entity);

        $form->bind($request);

        $result = array();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            try {
                $em->persist($entity);
                $em->flush();

                $result['success'] = true;
                $result['message'] = $message;
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
     * Displays a form to edit an existing Homestay entity.
     *
     * @Route("/{id}/edit", name="admin_homestay_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing Homestay entity.
     *
     * @Route("/{id}", name="admin_homestay_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a Homestay entity.
     *
     * @Route("/{id}", name="admin_homestay_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change Homestay status
     *
     * @Route("/{id}/status", name="admin_homestay_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_homestay_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
