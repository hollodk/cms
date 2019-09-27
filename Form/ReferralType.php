<?php

namespace Mh\PageBundle\Form;

use Mh\PageBundle\Entity\Referral;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferralType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attribute')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('user')
            ->add('referral')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Referral::class,
        ]);
    }
}
