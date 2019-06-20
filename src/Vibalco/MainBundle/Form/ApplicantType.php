<?php

namespace Vibalco\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApplicantType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'front.applicant.form.name'))
            ->add('email', null, array('label' => 'front.applicant.form.email'))
            ->add('message', null, array('label' => 'front.applicant.form.message'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\MainBundle\Entity\Applicant'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vibalco_mainbundle_applicant';
    }
}
