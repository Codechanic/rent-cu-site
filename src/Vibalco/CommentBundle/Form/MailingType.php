<?php

namespace Vibalco\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('contacts', 'select2',array('class'=>'Vibalco\CommentBundle\Entity\Contact', 'multiple'=>true, 
               'query_builder' => function (\Doctrine\ORM\EntityRepository $repository)
                 {
                     return $repository->createQueryBuilder('l')
                            ->where('l.enabled = ?1')
                            ->setParameter(1, '1');
                 }))
           ->add('title',null,array('label'=>"Asunto"))
           ->add('body', 'editor')          
            
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Vibalco\CommentBundle\Entity\Mailing'
        ));
    }

    public function getName()
    {
        return 'vibalco_commentbundle_mailingtype';
    }
}
