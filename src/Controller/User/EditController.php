<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Event\User\UserUpdated;
use App\Form\Users\UserEditType;
use App\Model\UserModel;
use App\Projector\UserProjector;
use App\Repository\UserRepository;
use App\Serializers\FormSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class EditController extends AbstractController
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly UserProjector        $projector,
        private readonly FormSerializer       $formSerializer
    ) {}

    /**
     * @throws ExceptionInterface
     * @ParamConverter("user", options={"mapping": {"user": "uuid"}})
     */
    #[Route('/api/management/users/{uuid}/edit', name: 'management.users.edit', methods:['get', 'post'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(User $user,Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $userModel = UserModel::createFromUser($user);
        $form = $this->formFactory->create(UserEditType::class, $userModel, [
            'roles' => $user->getRoles(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->projector->ApplyUserUpdated(new UserUpdated($userModel));

            if ($user instanceof User) {
                return new JsonResponse(['success' => true]);
            }

        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}