<?php

declare(strict_types = 1);

namespace App\Entity\Traits;

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTimeInterface;

trait CreatedUpdatedAtTrait
{
    use CreatedAtTrait;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(AbstractEntity::OUTPUT_GROUP)]
    private ?DateTimeInterface $updatedAt;

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}