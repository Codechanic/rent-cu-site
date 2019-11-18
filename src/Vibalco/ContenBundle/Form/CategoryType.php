<?php

namespace Vibalco\ContenBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add('translations', 'a2lix_translations_gedmo', array(
                    'translatable_class' => "Vibalco\ContenBundle\Entity\Category",
                    'fields' => array(
                        'name' => array()
                    )
                        )
                )
            ->add('alias')
            ->add('image','file',array('image_path' => 'webPath'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\ContenBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vibalco_contenbundle_category';
    }
}
