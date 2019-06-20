<?php

namespace Vibalco\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PromoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder               
            ->add('translations', 'a2lix_translations_gedmo', array(
                'label' => 'Traducción',
                'translatable_class' => "Vibalco\MainBundle\Entity\Promo",
                'fields' => array(
                    'name' => array('label' => 'Nombre'),
                    'description' => array('field_type' => 'textarea', 'attr' => array('class' => 'form-control'))
                )
            ))
            ->add('image', "file", array('image_path' => 'imageWebPath', 'label' => 'Foto'))
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
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\MainBundle\Entity\Promo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vibalco_mainbundle_promo';
    }
}
