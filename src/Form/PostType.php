<?php

namespace App\Form;

use App\Entity\Post;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', CKEditorType::class, [
                'label' => 'Post content'
            ])
            ->add('attachments', CollectionType::class, [
                'label' => false,
                'entry_type' => PostAttachmentType::class,
                'allow_add' => true,
                'allow_delete' => false,
                'entry_options' => [
                    'label' => false
                ],
                'prototype' => true,
                'prototype_name' => '__attachments__',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add post',
                'attr' => [
                    'class' => 'btn btn-outline-light'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
