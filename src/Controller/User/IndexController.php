<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Factory\DataTableFactory;
use App\Factory\DataTableRepositoryFactory;
use App\Model\DataTable\UserDataTable;
use App\Repository\DataTable\UserDataTableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IndexController extends AbstractController
{
    private UserDataTable $userDataTable;
    private UserDataTableRepository $userDataTableRepository;

    public function __construct(
        DataTableFactory $dataTableFactory,
        DataTableRepositoryFactory $dataTableRepositoryFactory,
    )
    {
        $userDataTable = $dataTableFactory->getDataTableForType(User::class);
        assert($userDataTable instanceof UserDataTable);
        $this->userDataTable = $userDataTable;
        $this->userDataTableRepository = $dataTableRepositoryFactory->getDataTableRepositoryForType(User::class);
    }

    #[Route('/api/management/users', name: 'management.users', methods:['get'])]
    #[IsGranted('ROLE_USER')]
    public function __invoke(EntityManagerInterface $entityManager): Response
    {
        $table = $this->userDataTable;
        $repository = $this->userDataTableRepository;
        $table->buildTable($repository);

        return $this->json([
            'datatable' => $table->getTable(),
        ]);
    }

}