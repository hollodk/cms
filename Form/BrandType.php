<?php

namespace Mh\PageBundle\Form;

use Mh\PageBundle\Entity\Brand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrandType extends AbstractType
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
            ->add('name', null, $opt)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Brand::class,
        ]);
    }
}
