<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/find_range', name: 'find_range_index', methods: ['GET'])]
class DataController extends AbstractController
{

    public function __invoke(): Response
    {
        return $this->render('/pages/find_range_index/detail.html.twig');
    }
}
