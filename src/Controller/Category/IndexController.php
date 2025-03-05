<?php

namespace App\Controller\Category;

use App\Entity\Category;
use App\Factory\DataTableFactory;
use App\Factory\DataTableRepositoryFactory;
use App\Model\DataTable\CategoryDataTable;
use App\Repository\DataTable\CategoryDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IndexController extends AbstractController
{
    private CategoryDataTable $dataTable;
    private CategoryDataTableRepository $dataTableRepository;

    public function __construct(
        DataTableFactory $dataTableFactory,
        DataTableRepositoryFactory $dataTableRepositoryFactory,
    )
    {
        $dataTable = $dataTableFactory->getDataTableForType(Category::class);
        assert($dataTable instanceof CategoryDataTable);
        $this->dataTable = $dataTable;
        $this->dataTableRepository = $dataTableRepositoryFactory->getDataTableRepositoryForType(Category::class);
    }
    #[Route('/api/management/categories', name: 'management.categories', methods:['get'])]
    #[IsGranted('ROLE_USER')]
    public function __invoke(EntityManagerInterface $entityManager): Response
    {
        $table = $this->dataTable;
        $repository = $this->dataTableRepository;
        $table->buildTable($repository);

        return $this->json([
            'datatable' => $table->getTable(),
        ]);
    }
}