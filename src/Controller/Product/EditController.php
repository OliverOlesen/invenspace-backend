<?php

namespace App\Controller\Product;

use App\Entity\Product;
use App\Event\Product\ProductUpdated;
use App\Form\Products\ProductEditType;
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

class EditController extends AbstractController
{
    public function __construct
    (
        private readonly FormFactoryInterface   $formFactory,
        private readonly FormSerializer         $formSerializer,
        private readonly ProductProjector       $projector
    )
    {}

    /**
     * @throws ExceptionInterface
     * @ParamConverter("product", options={"mapping": {"product": "uuid"}})
     */
    #[Route('/api/management/products/{uuid}/edit', name: 'management.products.edit', methods:['get', 'post'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Product $product, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $productModel = ProductModel::createFromProduct($product);
        $form = $this->formFactory->create(ProductEditType::class, $productModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $this->projector->ApplyProductUpdated(new ProductUpdated($productModel));

            if ($product instanceof Product) {
                return new JsonResponse(['success' => true]);
            }

        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}