<?php

namespace App\Controller\User;

use App\Entity\Contact;
use App\Entity\User;
use App\Event\User\UserDeleted;
use App\Model\UserModel;
use App\Projector\UserProjector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DeleteController extends AbstractController
{
    public function __construct(
        private readonly UserProjector $projector,
    ) {}

    /**
     * @ParamConverter("contact", options={"mapping": {"user": "uuid"}})
     */
    #[Route('/api/management/users/{uuid}/delete', name: 'management.users.delete', methods:['post'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(User $user, Request $request): JsonResponse
    {
        $model = UserModel::createFromUser($user);

        $isDeleted = $this->projector->ApplyUserDeleted(new UserDeleted($model));

        if ($isDeleted) {
            return new JsonResponse([
                'status' => 'success',
            ]);
        } else {
            return new JsonResponse([
                'status' => 'error',
            ]);
        }
    }
}