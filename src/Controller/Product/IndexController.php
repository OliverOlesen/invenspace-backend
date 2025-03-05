<?php

namespace App\Controller\Product;

use App\Entity\Product;
use App\Factory\DataTableFactory;
use App\Factory\DataTableRepositoryFactory;
use App\Model\DataTable\ProductDataTable;
use App\Repository\DataTable\ProductDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IndexController extends AbstractController
{
    private ProductDataTable $dataTable;
    private ProductDataTableRepository $dataTableRepository;

    public function __construct(
        DataTableFactory $dataTableFactory,
        DataTableRepositoryFactory $dataTableRepositoryFactory,
    )
    {
        $dataTable = $dataTableFactory->getDataTableForType(Product::class);
        assert($dataTable instanceof ProductDataTable);
        $this->dataTable = $dataTable;
        $this->dataTableRepository = $dataTableRepositoryFactory->getDataTableRepositoryForType(Product::class);
    }

    #[Route('/api/management/products', name: 'management.products', methods:['get'])]
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