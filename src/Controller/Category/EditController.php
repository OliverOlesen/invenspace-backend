<?php

namespace App\Controller\Category;

use App\Entity\Category;
use App\Event\Category\CategoryUpdated;
use App\Form\Category\CategoryEditType;
use App\Model\CategoryModel;
use App\Projector\CategoryProjector;
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
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly CategoryProjector    $projector,
        private readonly FormSerializer       $formSerializer
    ) {}

    /**
     * @throws ExceptionInterface
     * @ParamConverter("category", options={"mapping": {"category": "uuid"}})
     */
    #[Route('/api/management/categories/{uuid}/edit', name: 'management.categories.edit', methods:['get', 'post'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Category $category, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $model = CategoryModel::createFromCategory($category);
        $form = $this->formFactory->create(CategoryEditType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $this->projector->ApplyCategoryUpdated(new CategoryUpdated($model));

            if ($category instanceof Category) {
                return new JsonResponse(['success' => true]);
            }

        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}