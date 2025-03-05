<?php

namespace App\Controller\Category;

use App\Entity\Category;
use App\Event\Category\CategoryCreated;
use App\Form\Category\CategoryCreateType;
use App\Model\CategoryModel;
use App\Projector\CategoryProjector;
use App\Serializers\FormSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Limenius\Liform\Liform;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CreateController extends AbstractController
{

    public function __construct
    (
        private readonly FormFactoryInterface $formFactory,
        private readonly CategoryProjector $categoryProjector,
        private readonly FormSerializer $formSerializer
    )
    {}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/management/categories/create', name: 'management.categories.create', methods:['get', 'post'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $categoryModel = new CategoryModel();
        $form = $this->formFactory->create(CategoryCreateType::class, $categoryModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $this->categoryProjector->ApplyCategoryCreated(new CategoryCreated($categoryModel));
            if ($user instanceof Category) {
                return new JsonResponse(['success' => true]);
            }
        }

        return new JsonResponse([
            'form' => $this->formSerializer->serialize($form),
        ]);
    }
}