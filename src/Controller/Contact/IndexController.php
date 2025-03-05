<?php

namespace App\Controller\Contact;

use App\Entity\Contact;
use App\Factory\DataTableFactory;
use App\Factory\DataTableRepositoryFactory;
use App\Model\DataTable\ContactDataTable;
use App\Repository\DataTable\ContactDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IndexController extends AbstractController
{
    private ContactDataTable $dataTable;
    private ContactDataTableRepository $dataTableRepository;

    public function __construct(
        DataTableFactory $dataTableFactory,
        DataTableRepositoryFactory $dataTableRepositoryFactory,
    )
    {
        $dataTable = $dataTableFactory->getDataTableForType(Contact::class);
        assert($dataTable instanceof ContactDataTable);
        $this->dataTable = $dataTable;
        $this->dataTableRepository = $dataTableRepositoryFactory->getDataTableRepositoryForType(Contact::class);
    }

    #[Route('/api/management/contacts', name: 'management.contacts', methods:['get'])]
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