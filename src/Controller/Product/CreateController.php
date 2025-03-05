<?php

namespace App\Controller\Product;

use App\Entity\Product;
use App\Event\Product\ProductCreated;
use App\Form\Products\ProductCreateType;
use App\Model\ProductModel;
use App\Projector\ProductProjector;
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
        private readonly ProductProjector $productProjector,
        private readonly FormSerializer $formSerializer
    )
    {}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/management/products/create', name: 'management.products.create', methods:['get', 'post'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $productModel = new ProductModel();
        $form = $this->formFactory->create(ProductCreateType::class, $productModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $this->productProjector->ApplyProductInfoCreated(new ProductCreated($productModel));
            if ($user instanceof Product) {
                return new JsonResponse(['success' => true]);
            }
        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}