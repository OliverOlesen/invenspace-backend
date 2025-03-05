<?php

namespace App\Form\Users;

use App\Constants\UserRoleEnum;
use App\Form\Contact\ContactCreateType;
use App\Form\Contact\ContactEditType;
use App\Model\UserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true
            ]);
        $builder
            ->add('email', TextType::class, [
                'required' => true
            ]);
        $builder
            ->add('password', TextType::class, [
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserModel::class,
            'csrf_protection' => false
        ]);
    }
}