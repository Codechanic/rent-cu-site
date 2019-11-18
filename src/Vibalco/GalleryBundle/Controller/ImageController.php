<?php

namespace Vibalco\GalleryBundle\Controller;

use Vibalco\GalleryBundle\Entity\Image;
use Vibalco\GalleryBundle\Form\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/gallery")
 */
class ImageController extends Controller {

    /**
     * Lists all Image entities.
     *
     * @Route("/{owner}/index", name="admin_gallery")
     * @Template()
     */
    public function indexAction($owner) {
        $em = $this->getDoctrine()->getManager();
        $images = $em->getRepository('GalleryBundle:Image')->findBy(array('owner' => $owner));

        return array(
            'images' => $images,
            'owner' => $owner
        );
    }

    /**
     * Creates a new Image entity.
     *
     * @Route("/{owner}/create", name="admin_image_create")
     * @Method("POST")
     */
    public function createAction(Request $request, $owner) {
        $em = $this->getDoctrine()->getManager();
        $entity = new Image();

        $form = $this->createForm(new ImageType(), $entity);
        $form->submit($request);

        if (!$form->isValid()) {
            $result['success'] = false;
            $result['error'] = array('cause' => 'Invalid',
                'errors' => $this->get('admin.util')->getFormErrors($entity),
                'message' => 'Opps, existen campos con valores incorrectos.'
            );
        } else {
            try {
                $entity->setOwner($owner);
                $em->persist($entity);
                $em->flush();

                $result['success'] = true;
                $result['message'] = 'La imagen se ha adicionado correctamente.';
            } catch (\Exception $ex) {
                $result['success'] = false;
                $result['error'] = array('cause' => 'Exception', 'message' => $ex->getMessage());
            }
        }

        echo json_encode($result);
        die;
    }

    /**
     * Deletes a Image entity.
     *
     * @Route("/{id}/delete", name="admin_image_delete")
     * @Method("DELETE")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GalleryBundle:Image')->find($id);

        if (!$entity) {
            $result['success'] = false;
            $result['error'] = array('cause' => 'not_found', 'message' => 'Opps, la imagen no existe.');
        } else {
            try {
                $em->remove($entity);
                $em->flush();

                $result['success'] = true;
                $result['message'] = 'La imagen se ha eliminado correctamente.';
                $result['id'] = $id;
            } catch (\Exception $ex) {
                $result['success'] = false;
                $result['error'] = array('cause' => 'Exception', 'message' => $ex->getMessage());
            }
        }

        echo json_encode($result);
        die;
    }

}
