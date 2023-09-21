<?php

namespace App\Controller\MediaObject;

use App\Entity\BankExtraction;
use App\Service\BudgetFileParserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
final class CreateExtractionAction extends AbstractController
{
    public function __construct(
        private readonly BudgetFileParserInterface $budgetFileParserInterface
    ) {}

    public function __invoke(BankExtraction $bankExtraction, SerializerInterface $serializer): JsonResponse
    {
        $parsingResponse = $this->budgetFileParserInterface->parse($bankExtraction);
        $draftObject = $parsingResponse['draftObject'];
        $validatedTransactions = $parsingResponse['validatedTransactions'];

    return new JsonResponse($serializer->serialize([
        'budget' => $bankExtraction->getBudget(),
        'draftTransactions' => $draftObject,
        'validatedTransactions' => $validatedTransactions,
        ], 'json'), json: true);
    }
}
