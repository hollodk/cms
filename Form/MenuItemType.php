<?php

namespace Mh\PageBundle\Form;

use Mh\PageBundle\Entity\MenuItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuItemType extends AbstractType
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
            ->add('priority', null, $opt)
            ->add('menu', EntityType::class, [
                'attr' => $attr,
                'class' => 'MhPageBundle:Menu',
            ])
            ->add('page', EntityType::class, [
                'attr' => $attr,
                'class' => 'MhPageBundle:Page',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MenuItem::class,
        ]);
    }
}
