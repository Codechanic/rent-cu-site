<?php

namespace Vibalco\SliderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SlideType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('name')
           ->add('translations', 'a2lix_translations_gedmo', array(
                    'translatable_class' => "Vibalco\SliderBundle\Entity\Slide",
                   'fields' => array(
                        
                        'title' => array(),
                        'subtitle' => array(
                        )
                    )
                ))
            
            ->add('image','file',array('image_path' => 'webPath'))
            ->add('enabled')
           
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\SliderBundle\Entity\Slide'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vibalco_sliderbundle_slide';
    }
}
