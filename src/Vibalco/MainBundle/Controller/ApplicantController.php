<?php

namespace Vibalco\MainBundle\Controller;
;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\Applicant;
use Vibalco\MainBundle\Form\ApplicantType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * Applicant controller.
 *
 * @Route("/admin/{_locale}/applicant" , defaults={"_locale" = "en"})
 */
class ApplicantController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new Applicant(), new ApplicantType(), "MainBundle:Applicant");
    }

    private function _datatable() {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $qb->from($this->repository, 'p')->orderBy('p.id', 'desc');

        $datatable = $this->get('datatable')
                ->setFields(array(
                    'admin.applicant.name' => 'p.name',
                    'admin.applicant.email' => 'p.email',
                    'admin.applicant.message' => 'p.message',
                    '_identifier_' => 'p.id',
                ))
                ->setSearch(true)
                ->setMultiple(array(
                    'delete' => array(
                        'title' => 'Delete',
                        'route' => 'admin_applicant_delete_multiple' // path to multiple delete route
                    )
                ))
                ->setColumns(array(
                    'Nombre' => '1',
                    'Correo' => '2',
                    'Mensaje' => '3',
                ))

        ;
        $datatable->getQueryBuilder()->setDoctrineQueryBuilder($qb);
        return $datatable;
    }

    /**
     * Lists all Applicant entities.
     *
     * @Route("/", name="admin_applicant")
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_applicant_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all Applicant entities.
     *
     * @Route("/list", name="admin_applicant_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a Applicant entity.
     *
     * @Route("/{id}/show", name="admin_applicant_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new Applicant entity.
     *
     * @Route("/new", name="admin_applicant_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new Applicant entity.
     *
     * @Route("/create", name="admin_applicant_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        parent::createObject($request);
    }

    /**
     * Displays a form to edit an existing Applicant entity.
     *
     * @Route("/{id}/edit", name="admin_applicant_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing Applicant entity.
     *
     * @Route("/{id}", name="admin_applicant_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a Applicant entity.
     *
     * @Route("/{id}", name="admin_applicant_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change Applicant status
     *
     * @Route("/{id}/status", name="admin_applicant_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_applicant_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
