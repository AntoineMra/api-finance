<?php

namespace App\Service;

interface BudgetFileParserInterface
{
    /**
     * @return array<int>
     */
    public function parseExcelFile(): array;

}
