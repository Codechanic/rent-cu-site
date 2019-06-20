<?php

namespace Vibalco\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vibalco\AdminBundle\Entity\User;
use Vibalco\AdminBundle\Form\UserType;
use Vibalco\AdminBundle\Form\UserEditType;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Lsw\SecureControllerBundle\Annotation\Secure;

use Vibalco\AdminBundle\Form\UserPasswdType;
 
/**
 * User controller.
 *
 * @Route("/admin/{_locale}/user")
 * @Secure(roles="ROLE_ADMIN_USER", name="ADMIN USERS")
 */
class UserController extends Controller {

    /**
     * @return \Vibalco\DatatableBundle\Util\Datatable datatable
     */
    private function _datatable() {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $qb->from('AdminBundle:User', 'u')->orderBy('u.id', 'desc');

        $datatable = $this->get('datatable')
                ->setFields(array(
                            "admin.common.title"          => 'u.name',                        // Declaration for fields: 
                                                 //      "label" => "alias.field_attribute_for_dql"
                            "_identifier_"  => 'u.id')                          // you have to put the identifier field without label. Do not replace the "_identifier_"
                        )
                ->setSearch(TRUE);
                

        $datatable->getQueryBuilder()->setDoctrineQueryBuilder($qb);
        return $datatable;
    }

    /**
     * Lists all User entities.
     *
     * @Route("/", name="admin_user")
     * 
     * @Template()
     */
    public function indexAction() {
        $this->_datatable();
        return array();
    }

    /**
     * @Route("/grid", name="admin_user_grid")
     * 
     */
    public function gridAction() {
        return $this->_datatable()->execute();
    }

    /**
     * Lists all User entities.
     * 
     * @Route("/list", name="admin_user_list")
     * @Template()
     */
    public function listAction() {
        $this->_datatable();
        return array();
    }   

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/show", name="admin_user_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        return array(
            'entity' => $entity
        );
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method("POST")
     * @Template()
     */
    public function newAction() {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Encode password
     * 
     * @param User $entity
     */
    private function setSecurePassword(&$entity) {
        $confg = Yaml::parse(__DIR__ . '/../../../../app/config/security.yml');
        $params = $confg['security']['encoders'][get_class($entity)];
        $encoder = new MessageDigestPasswordEncoder($params['algorithm'], $params['encode_as_base64'], $params['iterations']);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="admin_user_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);
        $form->bind($request);

        $result = array();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            try {
                $this->setSecurePassword($entity);
                $em->persist($entity);
                $em->flush();
                
                $result['success'] = true;
                $result['message'] = "User created successfull";
                $result['id'] = $entity->getId();
            } catch (\Exception $ex) {
                $result['success'] = false;
                $result['error'] = array('cause' => 'Intern', 'message' => $ex->getMessage());
            }
        }else{
            
            $result['success'] = false;            
            $result['error'] = array('cause' => 'Intern', 'message' => $this->get('formError')->generateMessage($form));
       
        }

        echo json_encode($result);
        die;
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     *  
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserEditType(), $entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="admin_user_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminBundle:User')->find($id);        
        $result = array();        

        if (!$entity) {
            $result['success'] = false;
            $result['error'] = array('cause' => 'Not Found', 'message' => 'Unable to find User entity.');
            
        } else {
            $editForm = $this->createForm(new UserEditType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                try {
                    $em->persist($entity);
                    $em->flush();

                    $result['success'] = true;
                    $result['message'] = "User updated successfull";
                    $result['id'] = $entity->getId();
                } catch (\Exception $ex) {
                    $result['success'] = false;
                    $result['error'] = array('cause' => 'Intern', 'message' => $ex->getMessage());
                }
            }
        }

        echo json_encode($result);
        die;
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="admin_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminBundle:User')->find($id);
        $result = array();

        if (!$entity) {
            $result['success'] = false;
            $result['error'] = array('cause' => 'Not Found', 'message' => 'Unable to find User entity.');

        } else {
            try {
                $em->remove($entity);
                $em->flush();

                $result['success'] = true;
                $result['message'] = "User deleted successfull";
            } catch (\Exception $ex) {
                $result['success'] = false;
                $result['error'] = array('cause' => 'Intern', 'message' => $ex->getMessage());
            }
        }
        
        echo json_encode($result);
        die;
    }

    /**
     * Change user status
     *
     * @Route("/{id}/status", name="admin_user_status")
     * @Method("POST")
     */
    public function statusAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();        
        $entity = $em->getRepository('AdminBundle:User')->find($id);
        $result = array();
        
        if (!$entity) {
            $result['success'] = false;
            $result['error'] = array('cause' => 'Not Found', 'message' => 'Unable to find User entity.');
            
        } else {
            try {
                $status = !$entity->isEnabled();
                $entity->setEnabled($status);
                
                $em->persist($entity);
                $em->flush();

                $result['success'] = true;
                $result['status'] = $status;
                $result['message'] = 'Status changed to ' . ($status ? 'enabled' : 'disabled');
            } catch (\Exception $ex) {
                $result['success'] = false;
                $result['error'] = array('cause' => 'Intern', 'message' => $ex->getMessage());
            }
        }

        echo json_encode($result);
        die;
    }
    
    
    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/change_password", name="admin_user_change_password")

     * @Template()
     */
    public function change_passwordAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserPasswdType(), $entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/update_password/{id}", name="admin_user_update_password")

     */
    public function update_passwordAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminBundle:User')->find($id);
        $result = array();

        if (!$entity) {
            $result['success'] = false;
            $result['error'] = array('cause' => 'Not Found', 'message' => 'Unable to find User entity.');
        } else {
            $editForm = $this->createForm(new UserPasswdType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                try {
                    $this->setSecurePassword($entity);
                    $em->persist($entity);
                    $em->flush();

                    $result['success'] = true;
                    $result['message'] = "User updated successfull";
                    $result['id'] = $entity->getId();
                } catch (\Exception $ex) {
                    $result['success'] = false;
                    $result['error'] = array('cause' => 'Intern', 'message' => $ex->getMessage());
                }
            } else {
                $result['success'] = false;
                $result['error'] = array('cause' => 'Intern', 'message' => $this->get('formError')->generateMessage($editForm));
            }
        }

        echo json_encode($result);
        die;
    }

}
