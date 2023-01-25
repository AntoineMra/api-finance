<?php

namespace App\Service;

use App\Repository\BudgetRepository;
use App\Repository\CategoryRepository;
use App\Repository\DomainRepository;
use App\Repository\InvestRepository;

class DataStatistics implements DataStatisticsInterface
{
    public function getBudgetStatistics(BudgetRepository $repository): array
    {
        $statistics = [];


        return $statistics;
    }

    public function getInvestStatistics(InvestRepository $repository): array
    {
        $statistics = [];


        return $statistics;
    }

    public function getMainStatistics(): array
    {
        $statistics = [];


        return $statistics;
    }
}
