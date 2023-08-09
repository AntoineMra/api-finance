<?php

namespace App\Controller\MediaObject;

use App\Entity\BankExtraction;
use App\Service\BudgetFileParserInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
final class CreateExtractionAction extends AbstractController
{
    public function __construct(
        private BudgetFileParserInterface $budgetFileParserInterface
    ) {}

    public function __invoke(BankExtraction $bankExtraction): BankExtraction
    {
        $this->budgetFileParserInterface->parse($bankExtraction);
        return $bankExtraction;
    }
}