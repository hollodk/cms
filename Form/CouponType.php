<?php

namespace Mh\PageBundle\Form;

use Mh\PageBundle\Entity\Coupon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('couponCode')
            ->add('description')
            ->add('offerUrl')
            ->add('validFrom')
            ->add('validTo')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('seller')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Coupon::class,
        ]);
    }
}
