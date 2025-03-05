<?php

namespace App\Controller\Contact;

use App\Entity\Contact;
use App\Event\Contact\ContactCreated;
use App\Form\Contact\ContactCreateType;
use App\Model\ContactModel;
use App\Projector\ContactProjector;
use App\Serializers\FormSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CreateController extends AbstractController
{
    public function __construct
    (
        private readonly FormFactoryInterface $formFactory,
        private readonly ContactProjector $contactProjector,
        private readonly FormSerializer $formSerializer
    )
    {}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/management/contacts/create', name: 'management.contacts.create', methods:['get', 'post'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $contactModel = new ContactModel();
        $form = $this->formFactory->create(ContactCreateType::class, $contactModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $this->contactProjector->ApplyContactCreated(new ContactCreated($contactModel));
            if ($user instanceof Contact) {
                return new JsonResponse(['success' => true]);
            }
        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}