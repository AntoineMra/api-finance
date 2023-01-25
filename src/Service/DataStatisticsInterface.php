<?php

namespace App\Service;


use App\Repository\BudgetRepository;
use App\Repository\CategoryRepository;
use App\Repository\DomainRepository;
use App\Repository\InvestRepository;

interface DataStatisticsInterface
{
    /**
     * @return array<int>
     */
    public function getBudgetStatistics(BudgetRepository $repository): array;

    /**
     * @return array<int>
     */
    public function getInvestStatistics(InvestRepository $repository): array;

    /**
     * @return array<int>
     */
    public function getMainStatistics(): array;
}
