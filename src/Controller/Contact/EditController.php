<?php

namespace App\Controller\Contact;

use App\Entity\Contact;
use App\Event\Contact\ContactUpdated;
use App\Form\Contact\ContactEditType;
use App\Model\ContactModel;
use App\Projector\ContactProjector;
use App\Serializers\FormSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
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
        private readonly ContactProjector     $projector,
        private readonly FormSerializer       $formSerializer
    ) {}

    /**
     * @throws ExceptionInterface
     * @ParamConverter("contact", options={"mapping": {"contact": "uuid"}})
     */
    #[Route('/api/management/contacts/{uuid}/edit', name: 'management.contacts.edit', methods:['get', 'post'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Contact $contact, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $model = ContactModel::createFromContact($contact);
        $form = $this->formFactory->create(ContactEditType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $this->projector->ApplyContactUpdated(new ContactUpdated($model));

            if ($contact instanceof Contact) {
                return new JsonResponse(['success' => true]);
            }

        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}