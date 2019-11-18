<?php

namespace Vibalco\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HomestayExtraCostType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('translations', 'a2lix_translations_gedmo', array(
                    'translatable_class' => "Vibalco\MainBundle\Entity\HomestayExtraCost",
                    'fields' => array(
                        'name' => array()
                    )
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\MainBundle\Entity\HomestayExtraCost'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'vibalco_mainbundle_homestayextracost';
    }

}
