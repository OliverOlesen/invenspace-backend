<?php

namespace App\Controller\User\Profile;

use App\Entity\User;
use App\Event\User\UserUpdated;
use App\Form\Users\UserEditType;
use App\Model\UserModel;
use App\Projector\UserProjector;
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

    #[Route('/api/user/profile/edit', name: 'management.user.edit', methods:['get', 'post'])]
    #[IsGranted('ROLE_USER')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], 404);
            }

            $userModel = UserModel::createFromUser($user);
            $form = $this->formFactory->create(UserEditType::class, $userModel);
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

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to decode token: ' . $e->getMessage()], 401);
        } catch (ExceptionInterface $e) {
            return new JsonResponse(['error' => 'Failed to serialize values: ' . $e->getMessage()], 401);
        }
    }
}