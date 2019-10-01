<?php

namespace Mh\PageBundle\Form;

use Mh\PageBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attr = [
            'class' => 'form-control',
        ];

        $opt = [
            'attr' => $attr,
        ];

        $builder
            ->add('productReference', null, $opt)
            ->add('productName', null, $opt)
            ->add('price', null, $opt)
            ->add('priceDiscount', null, $opt)
            ->add('imageUrl', null, $opt)
            ->add('urlRedirect', null, $opt)
            ->add('description', null, $opt)
            ->add('brand', null, $opt)
            ->add('partner', null, $opt)
            ->add('tags', null, $opt)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
