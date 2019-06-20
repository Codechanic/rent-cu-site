<?php

namespace Vibalco\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProvinceType extends AbstractType
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
                'translatable_class' => "Vibalco\MainBundle\Entity\Province",
                    'fields' => array(
                        'name' => array('label' => 'Nombre')
                )
            ))
            ->add('order', null, array('label' => 'Orden de Aparición'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\MainBundle\Entity\Province'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vibalco_mainbundle_province';
    }
}
