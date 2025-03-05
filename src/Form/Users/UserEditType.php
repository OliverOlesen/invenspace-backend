<?php

namespace App\Form\Users;

use App\Constants\UserRoleEnum;
use App\Model\UserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserEditType extends AbstractType
{
    public function __construct
    (
        private readonly AuthorizationCheckerInterface $authorizationChecker
    ){ }

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

        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder
                ->add('roles', ChoiceType::class, [
                    'required' => true,
                    'multiple' => true,
                    'choices' => UserRoleEnum::getChoices(),
                    'data' => $options['roles'],
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserModel::class,
            'roles' => [],
            'csrf_protection' => false
        ]);

        $resolver->setAllowedTypes('roles', ['null', 'array']);
    }
}