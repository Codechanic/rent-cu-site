<?php

namespace Vibalco\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AntiqueCarType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', null, array('label' => 'Nombre'))
                ->add('owner', null, array('label' => 'Propietario'))
                ->add('image', "file", array('image_path' => 'webPath', 'label' => 'Imagen'))
                ->add('brand', 'select2', array('class' => "Vibalco\MainBundle\Entity\AntiqueCarBrand", 'label' => 'Marca'))
                ->add('municipality', 'select2', array('class' => "Vibalco\MainBundle\Entity\Municipality", 'label' => 'Municipio'))
                ->add('translations', 'a2lix_translations_gedmo', array(
                    'label' => 'Traducción',
                    'translatable_class' => "Vibalco\MainBundle\Entity\AntiqueCar",
                    'fields' => array(
                        'color' => array('label' => 'Color'),
                    )
                ))
                ->add('hardcover', null, array('label' => 'Techo duro'))
                ->add('convertible', null, array('label' => 'Descapotable'))
                ->add('year', null, array('label' => 'Año'))
                ->add('pricehour', null, array('label' => 'Precio por hora'))
                ->add('price8hour', null, array('label' => 'Precio por 8 horas'))
                ->add('phones', null, array('label' => 'Teléfonos'))
                ->add('email', null, array('label' => 'Correo'))
                ->add('enabled', null, array('label' => 'Activo'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\MainBundle\Entity\AntiqueCar'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'vibalco_mainbundle_antiquecar';
    }

}
