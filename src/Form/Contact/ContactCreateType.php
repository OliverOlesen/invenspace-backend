<?php

namespace App\Form\Contact;

use App\Form\Address\AddressCreateType;
use App\Model\ContactModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true
            ]);
        $builder
            ->add('middleName', TextType::class, [
                'required' => false
            ]);
        $builder
            ->add('lastName', TextType::class, [
                'required' => true
            ]);
        $builder
            ->add('email', TextType::class, [
                'required' => true
            ]);
        $builder
            ->add('mobile', TextType::class, [
                'required' => false
            ]);
        $builder
            ->add('address', AddressCreateType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactModel::class,
            'csrf_protection' => false
        ]);
    }
}