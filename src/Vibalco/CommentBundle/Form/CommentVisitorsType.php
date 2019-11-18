<?php

namespace Vibalco\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentVisitorsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client')
            ->add('phone')
            ->add('email')
            ->add('comment')
            ->add('date')
            ->add('read')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\CommentBundle\Entity\CommentVisitors'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vibalco_commentbundle_commentvisitors';
    }
}
