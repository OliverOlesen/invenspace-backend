<?php

namespace App\Controller\OrderLine;

use App\Entity\OrderLine;
use App\Factory\DataTableFactory;
use App\Factory\DataTableRepositoryFactory;
use App\Model\DataTable\OrderLineDataTable;
use App\Repository\DataTable\OrderLineDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IndexController extends AbstractController
{
    private OrderLineDataTable $dataTable;
    private OrderLineDataTableRepository $dataTableRepository;

    public function __construct(
        DataTableFactory $dataTableFactory,
        DataTableRepositoryFactory $dataTableRepositoryFactory,
    )
    {
        $dataTable = $dataTableFactory->getDataTableForType(OrderLine::class);
        assert($dataTable instanceof OrderLineDataTable);
        $this->dataTable = $dataTable;
        $this->dataTableRepository = $dataTableRepositoryFactory->getDataTableRepositoryForType(OrderLine::class);
    }

    #[Route('/api/management/order-lines', name: 'management.order-lines', methods:['get'])]
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