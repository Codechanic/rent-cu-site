<?php

namespace Vibalco\MainBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class PromoDiscountType extends PromoType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        parent::buildForm($builder, $options);
        
        $builder
            ->add('translations', 'a2lix_translations_gedmo', array(
                'label' => 'Traducción',
                'translatable_class' => "Vibalco\MainBundle\Entity\Promo",
                'fields' => array(
                    'name' => array('label' => 'Nombre'),
                    'description' => array('field_type' => 'hidden', 'attr' => array('class' => 'form-control'))
                )
            ))
            ->add('image', "file", array('image_path' => 'imageWebPath', 'label' => 'Foto'))
            ->add('discount', null, array('label' => 'Valor de Descuento'))
            ->add('price', null, array('label' => 'Precio'))
            ->add('homestay', 'select2', array('class' => "Vibalco\MainBundle\Entity\Homestay", 'label' => 'Casa', 'required' => false))
            ->add('url', null, array('label' => 'Url'))
            ->add('from_date', 'date', array(
                'label' => 'Desde', 
                'widget' => 'single_text', 
                'format' => 'dd/MM/yyyy', 
                'attr' => array('class' => 'datepicker')
            ))
            ->add('to_date', 'date', array(
                'label' => 'Hasta', 
                'widget' => 'single_text', 
                'format' => 'dd/MM/yyyy', 
                'attr' => array('class' => 'datepicker')
            ))
        ;
    }
}
