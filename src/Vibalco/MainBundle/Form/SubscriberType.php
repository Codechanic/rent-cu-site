<?php

namespace Vibalco\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SubscriberType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, array('label' => 'Correo'))
            ->add('enabled', null, array('label' => 'Activo'))
            ->add('locked', null, array('label' => 'Bloqueado'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\MainBundle\Entity\Subscriber'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vibalco_mainbundle_subscriber';
    }
}
