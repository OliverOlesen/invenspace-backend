<?php

namespace App\Controller\Security;

use App\Entity\Contact;
use App\Event\User\UserPasswordReset;
use App\Form\Users\Security\PasswordResetType;
use App\Model\PasswordResetModel;
use App\Model\UserModel;
use App\Projector\UserProjector;
use App\Serializers\FormSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ResetPasswordController extends AbstractController
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly UserProjector        $projector,
        private readonly FormSerializer       $formSerializer
    ) {}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/security/reset/password', name: 'security.reset.password', methods:['get', 'post'])]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $passwordResetModel = new PasswordResetModel();
        $form = $this->formFactory->create(PasswordResetType::class, $passwordResetModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->projector->GetUserFromAuthenticationToken($passwordResetModel->token);

            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $userModel = UserModel::createFromUser($user);
            $userModel->password = $passwordResetModel->password;

            $this->projector->SetNewUserPassword(new UserPasswordReset($userModel));

            return new JsonResponse(['success' => true, 'message' => 'Password has been reset']);
        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}