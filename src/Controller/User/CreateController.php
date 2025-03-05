<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Event\User\UserCreated;
use App\Form\Users\UserCreateType;
use App\Model\UserModel;
use App\Projector\UserProjector;
use App\Serializers\FormSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CreateController extends AbstractController
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly UserProjector $userProjector,
        private readonly FormSerializer $formSerializer,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/management/users/create', name: 'management.users.create', methods:['get', 'post'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $userModel = UserModel::createNewUser();
        $form = $this->formFactory->create(UserCreateType::class, $userModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $this->userProjector->ApplyUserCreated(new UserCreated($userModel));
            if ($user instanceof User) {
                return new JsonResponse(['success' => true]);
            }
        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}