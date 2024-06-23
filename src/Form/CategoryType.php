<?php

namespace App\Form;

use App\Entity\Category;
use App\Trait\UserProviderTrait;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    use UserProviderTrait;

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $user = $this->getUser();

        $builder->add('name', TextType::class);
        if (null !== $this->getUser()) {
            $builder
                ->add('secure', CheckboxType::class, [
                    'required' => false
                ]);
        }

        if (null !== $builder->getData()?->getId()) {
            $builder
                ->add('parent', EntityType::class, [
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
                'attr' => [
                    'class' => 'btn btn-outline-light'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Category::class
        ]);
    }
}