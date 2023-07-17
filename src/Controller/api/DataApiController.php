<?php

declare(strict_types=1);

namespace App\Controller\api;

use App\Core\Exception\ApiException;
use App\Service\ScopeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/scope/period/{periodValue}', name: 'api_find_range', methods: ['GET'])]
class DataApiController extends AbstractController
{
    public function __construct(
        private readonly ScopeService $scopeService
    ) {
    }

    public function __invoke(int $test): JsonResponse
    {
        $data = $this->scopeService->getScopesByPeriod($test);
        return $this->json($data);
    }
}
