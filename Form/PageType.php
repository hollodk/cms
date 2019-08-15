<?php

namespace Mh\PageBundle\Form;

use Mh\PageBundle\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
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
            ->add('isFrontpage')
            ->add('site', EntityType::class, [
                'attr' => $attr,
                'class' => 'Mh\PageBundle:Site',
            ])
            ->add('header', null, $opt)
            ->add('content', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 30,
                    'class' => $attr['class'],
                ],
            ])
            ->add('attribute', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 30,
                    'class' => $attr['class'],
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
