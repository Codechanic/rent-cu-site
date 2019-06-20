<?php
namespace Vibalco\AdminBundle\Form\Extension;

 
 

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageTypeExtension extends AbstractTypeExtension
{
    /**
     * Devuelve el nombre del tipo que será extendido.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'file';
    }

    /**
     * Añade la opción image_path
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('image_path'));
    }

    /**
     * Pase la URL de la imagen a la vista
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('image_path', $options)) {
            $parentData = $form->getParent()->getData();

            if (null !== $parentData) {
                $accessor = PropertyAccess::getPropertyAccessor();
                $imageUrl = $accessor->getValue($parentData, $options['image_path']);
            } else {
                 $imageUrl = null;
            }

            // configura una variable "image_url" que debe estar disponible al dibujar este campo
          $view->vars['image_url'] = $imageUrl;
        }
    }

}


