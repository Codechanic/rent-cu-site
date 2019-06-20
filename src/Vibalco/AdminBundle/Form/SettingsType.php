<?php

namespace Vibalco\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations_gedmo', array(
                'label' => 'admin.a2lix.translations',
                'translatable_class' => "Vibalco\AdminBundle\Entity\Settings",
                'fields' => array(
                    'name' => array('label' => 'admin.settings.name'),
                    'address' => array('label' => 'admin.settings.address', 'field_type' => 'textarea', 'attr' => array('class' => 'form-control input-sm')),                  
                    'contactdescription' => array('label' => 'admin.settings.contactdescription', 'attr' => array('class' => 'form-control input-sm')),
                    'aboutus' => array('label' => 'admin.settings.aboutus', 'attr' => array('class' => 'editor')),
                    'meta_title' => array('label' => 'admin.settings.metatitle'),
                    'meta_keywords' => array('label' => 'admin.settings.metakeywords'),
                    'meta_description' => array('field_type' => 'textarea', 'label' => 'admin.settings.metadescription', 'attr' => array('class' => 'form-control input-sm')),
                )
            ))
            ->add('facebook', null, array('label' => 'admin.settings.facebook'))
            ->add('twitter', null, array('label' => 'admin.settings.twitter'))
            ->add('tripadvisor', null, array('label' => 'admin.settings.tripadvisor'))
            ->add('googleplus', null, array('label' => 'admin.settings.googleplus'))
            ->add('email', null, array('label' => 'admin.settings.email'))
            ->add('phones', null, array('label' => 'admin.settings.phones'))
                
            ->add('adminemail', null, array('label' => 'admin.settings.adminemail'))
            ->add('exchangecuc', null, array('label' => 'admin.settings.exchangecuc'))

//            ->add('offline', null, array('label' => 'admin.settings.offline'))
                
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\AdminBundle\Entity\Settings'
        ));
    }

    public function getName()
    {
        return 'vibalco_adminbundle_settingstype';
    }
}
