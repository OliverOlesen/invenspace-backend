<?php

namespace App\Form\Address;

use App\Model\AddressModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', TextType::class, [
                'required' => true,
            ]);
        $builder
            ->add('city', TextType::class, [
                'required' => true,
            ]);
        $builder
            ->add('postal_code', TextType::class, [
                'required' => true,
            ]);
        $builder
            ->add('state', TextType::class, [
                'required' => false,
            ]);
        $builder
            ->add('street', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'street name',
                ]
            ]);
        $builder
            ->add('house_number', TextType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddressModel::class,
            'csrf_protection' => false
        ]);
    }
}