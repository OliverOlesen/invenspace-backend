<?php

namespace App\Form\Category;

use App\Model\CategoryModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Category Type',
                ]
            ]);
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ]);
        $builder
            ->add('description', TextType::class, [
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryModel::class,
            'csrf_protection' => false
        ]);
    }
}