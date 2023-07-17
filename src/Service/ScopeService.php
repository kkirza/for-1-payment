<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ScopeRepository;

class ScopeService
{
    public function __construct(
        private ScopeRepository $scopeRepository
    ) {
    }

    public function getScopesByPeriod(int $period): array
    {
        return $this->scopeRepository->findByPeriod($period);
    }
}
