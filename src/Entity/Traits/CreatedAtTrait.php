<?php

declare(strict_types = 1);

namespace App\Entity\Traits;

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTimeInterface;

trait CreatedAtTrait
{
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime')]
    #[Groups(AbstractEntity::OUTPUT_GROUP)]
    private DateTimeInterface $createdAt;

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}