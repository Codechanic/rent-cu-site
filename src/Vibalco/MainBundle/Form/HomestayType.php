<?php

namespace Vibalco\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HomestayType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', null, array('label' => 'Nombre'))
                ->add('image', "file", array('image_path' => 'webPath', 'label' => 'Imagen'))
                ->add('municipality', 'select2', array('class' => "Vibalco\MainBundle\Entity\Municipality", 'label' => 'Municipio'))
                ->add('translations', 'a2lix_translations_gedmo', array(
                    'label' => 'Traducción',
                    'translatable_class' => "Vibalco\MainBundle\Entity\Homestay",
                    'fields' => array(
                        'description' => array('field_type' => 'editor'),
                        'address' => array('label' => 'Dirección'),
                        
                        'meta_title' => array('label' => 'Meta Título'),
                        'meta_description' => array('label' => 'Meta Descripción'),
                        'meta_keywords' => array('label' => 'Meta Palabras Clave'),
                    )
                ))
                
                ->add('owner', null, array('label' => 'Propietario'))
                ->add('email', null, array('label' => 'Correo'))
                ->add('phones', null, array('label' => 'Teléfonos'))
                ->add('account', null, array('label' => 'Datos bancarios'))
                ->add('rooms', null, array('label' => 'Habitaciones'))
                ->add('promo', null, array('label' => 'Promocionar'))
                ->add('comision', null, array('label' => 'Comisión'))
                
                ->add('acommodation', 'select2', array('class' => "Vibalco\MainBundle\Entity\AcommodationType", 'label' => 'Alojamiento'))
                ->add('location', 'select2', array('class' => "Vibalco\MainBundle\Entity\Location", 'label' => 'Modalidad'))
                ->add('freeservices', 'select2', array('class' => "Vibalco\MainBundle\Entity\HomestayFreeService", 'multiple' => true, 'label' => 'Sevicios Gratuitos'))
                ->add('extracosts', 'select2', array('class' => "Vibalco\MainBundle\Entity\HomestayExtraCost", 'multiple' => true, 'label' => 'Costo Extra'))
                ->add('notoffereds', 'select2', array('class' => "Vibalco\MainBundle\Entity\HomestayNotOffered", 'multiple' => true, 'label' => 'No Ofertado'))
                ->add('places', 'select2', array('class' => "Vibalco\MainBundle\Entity\Place", 'multiple' => true, 'label' => 'Lugares de Interés'))
                ->add('breakfastprice', null, array('label' => 'Precio Desayuno'))
                ->add('extrabedprice', null, array('label' => 'Precio Cama Extra'))
                ->add('bedchildprice', null, array('label' => 'Precio Cama Para Niño'))
                ->add('showcontact', null, array('label' => 'Mostrar Contactos'))
                ->add('rank', null, array('label' => 'Ranking'))

                ->add('enabled', null, array('label' => 'Activo'))
            ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\MainBundle\Entity\Homestay'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'vibalco_mainbundle_homestay';
    }

}
