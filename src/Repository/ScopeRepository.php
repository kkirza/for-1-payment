<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Scope;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ScopeRepository
{
    /** @var EntityRepository<Scope> */
    private readonly EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $em)
    {
        $this->repository = $this->em->getRepository(Scope::class);
    }

    public function save(Scope $scope): void
    {
        $this->em->persist($scope);
    }

    /**
     * @return Scope[]
     */
    public function findByPeriod(int $period): array
    {
        return $this->repository
            ->createQueryBuilder('s')
            ->where(":period >= s.minValue and :period <= s.maxValue")
            ->setParameter(':period', $period)
            ->getQuery()
            ->getArrayResult();
    }
}
