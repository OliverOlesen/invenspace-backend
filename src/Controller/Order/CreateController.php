<?php

namespace App\Controller\Order;

use App\Entity\Order;
use App\Event\Order\OrderCreated;
use App\Form\Order\OrderCreateType;
use App\Model\OrderModel;
use App\Projector\OrderProjector;
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
        private readonly OrderProjector $orderProjector,
        private readonly FormSerializer $formSerializer
    )
    {}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/management/orders/create', name: 'management.orders.create', methods:['get', 'post'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $orderModel = new OrderModel();
        $form = $this->formFactory->create(OrderCreateType::class, $orderModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $this->orderProjector->ApplyOrderCreated(new OrderCreated($orderModel));
            if ($user instanceof Order) {
                return new JsonResponse(['success' => true]);
            }
        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}