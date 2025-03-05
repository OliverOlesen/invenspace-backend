<?php

namespace App\Controller\Security;

use App\Entity\Contact;
use App\Entity\User;
use App\Event\User\UserPasswordReset;
use App\Form\Users\Security\PasswordResetRequestType;
use App\Model\PasswordResetModel;
use App\Model\PasswordResetRequestModel;
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

class RequestPasswordResetController extends AbstractController
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly UserProjector        $projector,
        private readonly FormSerializer       $formSerializer,
    ) {}

    /**
     * @throws TransportExceptionInterface
     * @throws ExceptionInterface
     */
    #[Route('/api/security/request/reset', name: 'security.request.reset', methods:['get', 'post'])]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $passwordResetRequestModel = new PasswordResetRequestModel();
        $form = $this->formFactory->create(PasswordResetRequestType::class, $passwordResetRequestModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $passwordResetRequestModel->email]);

            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $userModel = UserModel::createFromUser($user);

            $this->projector->ApplyPasswordResetRequest(new UserPasswordReset($userModel));

            return new JsonResponse(['success' => true, 'message' => 'Password reset email sent']);
        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}