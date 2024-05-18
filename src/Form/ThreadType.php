<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Thread;
use App\Trait\UserProviderTrait;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreadType extends AbstractType
{
    use UserProviderTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->getUser();

        $builder
            ->add('title', TextType::class);

        if ($user) {
            $builder
                ->add('secure', CheckboxType::class, [
                    'required' => false
                ]);
        }

        if ($builder->getData()?->getId() > 0) {
            $builder
                ->add('category', EntityType::class, [
                    'class' => Category::class,
                    'required' => false,
                    'placeholder' => '-- Select category --',
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        $qb = $er->createQueryBuilder('c');
                        if (null === $user) {
                            $qb->andWhere('c.secure = 0 OR c.secure IS NULL');
                        }
                        return $qb;
                    }
                ]);
        }

        $builder
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-outline-light'
                ]
            ])
            ->add('saveAndReturn', SubmitType::class, [
                'label' => 'Save and return',
                'attr' => [
                    'class' => 'btn btn-outline-secondary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Thread::class,
        ]);
    }
}
