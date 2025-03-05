<?php

namespace App\Form\Products;

use App\Entity\Category;
use App\Form\Category\CategoryCreateType;
use App\Model\ProductModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ]);
        $builder
            ->add('price', NumberType::class, [
                'required' => true
            ]);
        $builder
            ->add('stock', NumberType::class, [
                'required' => true
            ]);
        $builder
            ->add('description', TextType::class, [
                'required' => true
            ]);
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class, // Assuming you have Category class
                'choice_label' => 'name', // Use 'name' property of Category entity
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => ProductModel::class,
            'csrf_protection' => false
        ]);
    }
}