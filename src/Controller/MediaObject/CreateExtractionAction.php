<?php

namespace App\Controller\MediaObject;

use App\Entity\BankExtraction;
use App\Service\BudgetFileParserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

#[AsController]
final class CreateExtractionAction extends AbstractController
{
    public function __construct(
        private readonly BudgetFileParserInterface $budgetFileParserInterface
    ) {}

    public function __invoke(BankExtraction $bankExtraction): JsonResponse
    {
        $transactions = $this->budgetFileParserInterface->parse($bankExtraction);
        //TODO: Possibly adapt the return with Front End constraints

        return new JsonResponse([
            'budget' => $bankExtraction->getBudget(),
            'month' => $bankExtraction->getMonth(),
            'transactions' => $transactions,
        ]);
    }
}
