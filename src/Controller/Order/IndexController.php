<?php

namespace App\Controller\Order;

use App\Entity\Order;
use App\Factory\DataTableFactory;
use App\Factory\DataTableRepositoryFactory;
use App\Model\DataTable\OrderDataTable;
use App\Repository\DataTable\OrderDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IndexController extends AbstractController
{
    private OrderDataTable $dataTable;
    private OrderDataTableRepository $dataTableRepository;

    public function __construct(
        DataTableFactory $dataTableFactory,
        DataTableRepositoryFactory $dataTableRepositoryFactory,
    )
    {
        $dataTable = $dataTableFactory->getDataTableForType(Order::class);
        assert($dataTable instanceof OrderDataTable);
        $this->dataTable = $dataTable;
        $this->dataTableRepository = $dataTableRepositoryFactory->getDataTableRepositoryForType(Order::class);
    }

    #[Route('/api/management/orders', name: 'management.orders', methods:['get'])]
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