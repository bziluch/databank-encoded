<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('secure', CheckboxType::class, [
                'required' => false
            ]);

        if (null !== $builder->getData()?->getId()) {
            $builder
                ->add('parent', EntityType::class, [
                    'class' => Category::class,
                    'required' => false,
                    'placeholder' => '-- Select category --'
                ]);
        }

        $builder
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-light'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class
        ]);
    }
}