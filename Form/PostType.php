<?php

namespace Mh\PageBundle\Form;

use Mh\PageBundle\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
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
            ->add('title', null, $opt)
            ->add('content', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 15,
                    'class' => $attr['class'].' summernote',
                ],
            ])
            ->add('attribute', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 15,
                    'class' => $attr['class'].' json',
                ],
            ])
            ->add('tags', EntityType::class, [
                'class' => 'Mh\PageBundle:Tag',
                'multiple' => true,
                'attr' => [
                    'size' => 5,
                    'class' => $attr['class'].' select2',
                ],
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
