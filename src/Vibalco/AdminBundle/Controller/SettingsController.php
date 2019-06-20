<?php

namespace Vibalco\AdminBundle\Controller;
;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\AdminBundle\Entity\Settings;
use Vibalco\AdminBundle\Form\SettingsType;
use Vibalco\AdminBundle\Manager\AdminManager;

/**
 * Settings controller.
 *
 * @Route("/admin/{_locale}/settings")
 */
class SettingsController extends AdminManager {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    function __construct() {
        parent::__construct(new Settings(), new SettingsType(), "AdminBundle:Settings");
    }

    private function _datatable() {
        return parent::datatable();
    }

    /**
     * Lists all Settings entities.
     *
     * @Route("/", name="admin_settings")
     * @Template()
     */
    public function indexAction() {


        $this->_datatable();
        return array('data' => $this->_datatable()->getColumns());
    }

    /**
     * @Route("/grid", name="admin_settings_grid")
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all Settings entities.
     *
     * @Route("/list", name="admin_settings_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array();
    }

    /**
     * Finds and displays a Settings entity.
     *
     * @Route("/{id}/show", name="admin_settings_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository($this->repository)->findAll();        
        $entity = count($entities) > 0 ? $entities[0] : null;

        if (!$entity) {
           $entity = new Settings();
           
           $em->persist($entity);
           $em->flush();
        }
        
        $module = explode(":", $this->repository);
        return array(
            'entity' => $entity,
            'module' => strtolower($module[1]),
        );
    }

    /**
     * Displays a form to edit an existing Settings entity.
     *
     * @Route("/{id}/edit", name="admin_settings_edit")     *
     * @Template()
     */
    public function editAction($id) {
        return parent::editObject($id);
    }

    /**
     * Edits an existing Settings entity.
     *
     * @Route("/{id}", name="admin_settings_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        return parent::updateObject($request, $id);
    }

    /**
     * Deletes a Settings entity.
     *
     * @Route("/{id}", name="admin_settings_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        parent::deleteObject($request, $id);
    }

    /**
     * Uncomment This if you have attribute enabled
     * Change Settings status
     *
     * @Route("/{id}/status", name="admin_settings_status")
     * @Method("POST")
     */
    /* public function statusAction(Request $request, $id) {
      return parent::statusObject($request, $id);
      } */

    /**
     * Deletes a Navegadores entity multiple.
     *
     * @Route("/delete_multiple", name="admin_settings_delete_multiple")
     * @Method("POST")
     */
    public function deletemultipleAction() {
        $data = $this->getRequest()->get('dataTables');
        $ids = $data['actions'];
        parent::delteObjects($this->getRequest(), $ids);
    }

}
