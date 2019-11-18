<?php

namespace Vibalco\AdminBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vibalco\GalleryBundle\Model\GalleryInterface;

class AdminManager extends Controller {

    protected $object;
    protected $form;
    protected $repository;

    function __construct($object, $form, $repository) {
        $this->object = $object;
        $this->form = $form;
        $this->repository = $repository;
        
    }

    public function NewForm() {
        $entity = $this->object;
        $form = $this->createForm(new $this->form, $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    public function createObject($request, $message="Object created successfull") {
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
        }else {
            $result['success'] = false;            
            $result['error'] = array('cause' => 'Intern', 'message' => $this->get('formError')->generateMessage($form));
        }
        echo json_encode($result);
        die;
    }

    public function showObject($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }
        $module = explode(":", $this->repository);
        return array(
            'entity' => $entity,
            'module' => strtolower($module[1]),
        );
    }

    public function editObject($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }
        $editForm = $this->createForm(new $this->form, $entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    public function updateObject($request, $id, $message="Object updated successfull") {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->repository)->find($id);
        $result = array();

        if (!$entity) {
            $result['success'] = false;
            $result['error'] = array('cause' => 'Not Found', 'message' => 'Unable to find Post entity.');
        } else {
            $editForm = $this->createForm(new $this->form, $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
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
            }
            else {
            $result['success'] = false;            
            $result['error'] = array('cause' => 'Intern', 'message' => $this->get('formError')->generateMessage($editForm));
        }
        }

        echo json_encode($result);
        die;
    }

    public function deleteObject($request, $id, $message="Object delete successfull" ) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->repository)->find($id);
        $result = array();

        if (!$entity) {
            $result['success'] = false;
            $result['error'] = array('cause' => 'Not Found', 'message' => 'Unable to find Entity.');
        } else {
            try {
                $em->remove($entity);                
                $this->removeGallery($entity);                
                $em->flush();

                $result['success'] = true;
                $result['message'] = $message;
            } catch (\Exception $ex) {
                $result['success'] = false;
                $result['error'] = array('cause' => 'Intern', 'message' => $ex->getMessage());
            }
        }

        echo json_encode($result);
        die;
    }

    public function delteObjects($request, $ids) {
        foreach ($ids as $id) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository($this->repository)->find($id);
            if ($entity) {
                $em->remove($entity);
                $this->removeGallery($entity);
                $em->flush();
            }
            
        }
        echo json_encode("OK");
        die;
    }
    
    private function removeGallery($entity) 
    {
        if($entity instanceof GalleryInterface)
        {
            $owner = $entity->galleryOwner();
            
            $em = $this->getDoctrine()->getEntityManager();
 
            $entities = $em->getRepository('GalleryBundle:Image')
                           ->findBy(array('owner' => $owner));

            foreach ($entities as $entity) {
                $em->remove($entity);
            }
        }
    }

    public function statusObject($request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->repository)->find($id);
        $result = array();

        if (!$entity) {
            $result['success'] = false;
            $result['error'] = array('cause' => 'Not Found', 'message' => 'Unable to find Entity.');
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

    public function datatable(array $fields = null) {
        
        if(!isset($fields)) {
            $fields = array('admin.common.title' => 'p.name');
        }
        
        $fields['_identifier_'] = 'p.id';
        
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $qb->from($this->repository, 'p')->orderBy('p.id', 'desc');

        $datatable = $this->get('datatable')
                ->setFields($fields)
                ->setSearch(true);

        $datatable->getQueryBuilder()->setDoctrineQueryBuilder($qb);
        return $datatable;
    }

}
