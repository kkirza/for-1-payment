<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table('scope')]
#[ORM\Entity]
class Scope
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint', nullable: false)]
    private readonly int $id;

    public function __construct(
        #[ORM\Column(type: 'bigint')]
        private int $minValue,
        #[ORM\Column(type: 'bigint')]
        private int $maxValue
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMinValue(): int
    {
        return $this->minValue;
    }

    public function setMinValue(int $minValue): self
    {
        $this->minValue = $minValue;

        return $this;
    }

    public function getMaxValue(): int
    {
        return $this->maxValue;
    }

    public function setMaxValue(int $maxValue): self
    {
        $this->maxValue = $maxValue;

        return $this;
    }
}
