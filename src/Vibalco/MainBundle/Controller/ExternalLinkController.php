<?php

namespace Vibalco\MainBundle\Controller;
;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\MainBundle\Entity\ExternalLink;
use Vibalco\MainBundle\Form\ExternalLinkType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * ExternalLink controller.
 *
 * @Route("/admin/{_locale}/externallink" , defaults={"_locale" = "en"})
 */
class ExternalLinkController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new ExternalLink(), new ExternalLinkType(), "MainBundle:ExternalLink");
    }

    private function _datatable() {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $qb->from($this->repository, 'p')->orderBy('p.id', 'desc');

        $datatable = $this->get('datatable')
                ->setFields(array(
                    'Nombre' => 'p.name',
                    'Url' => 'p.url',
                    'Orden' => 'p.norder',
                    '_identifier_' => 'p.id',
                ))
                ->setSearch(true)
                ->setMultiple(
                    array(
                        'delete' => array(
                            'title' => 'Eliminar',
                            'route' => 'admin_externallink_delete_multiple' // path to multiple delete route
                        )
                    )
                )
                ->setColumns(array(
                    'Nombre' => '1',
                    'Url' => '2',
                    'Orden' => '3',
                ))

        ;
        $datatable->getQueryBuilder()->setDoctrineQueryBuilder($qb);
        return $datatable;
    }

    /**
     * Lists all ExternalLink entities.
     *
     * @Route("/", name="admin_externallink")
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_externallink_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all ExternalLink entities.
     *
     * @Route("/list", name="admin_externallink_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * Finds and displays a ExternalLink entity.
     *
     * @Route("/{id}/show", name="admin_externallink_show")
     * @Template()
     */
    public function showAction($id) {
        return parent::showObject($id);
    }

    /**
     * Displays a form to create a new ExternalLink entity.
     *
     * @Route("/new", name="admin_externallink_new")
     * @Template()
     */
    public function newAction() {
        return parent::NewForm();
    }

    /**
     * Creates a new ExternalLink entity.
     *
     * @Route("/create", name="admin_externallink_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        parent::createObject($request);
    }

    /**
     * Displays a form to edit an existing ExternalLink entity.
     *
     * @Route("/{id}/edit", name="admin_externallink_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing ExternalLink entity.
     *
     * @Route("/{id}", name="admin_externallink_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a ExternalLink entity.
     *
     * @Route("/{id}", name="admin_externallink_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change ExternalLink status
     *
     * @Route("/{id}/status", name="admin_externallink_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_externallink_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
