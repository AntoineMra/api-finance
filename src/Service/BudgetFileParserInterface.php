<?php

namespace App\Service;

use App\Entity\BankExtraction;

interface BudgetFileParserInterface
{
    /**
     * @return array<int>
     */
    public function parse(BankExtraction $bankExtraction): array;

}
