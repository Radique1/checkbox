<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\BookImage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method BookImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookImage[]    findAll()
 * @method BookImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookImageRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(BookImage::class));
    }
}