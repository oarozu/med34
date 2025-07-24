<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Centro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class CentroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Centro::class);
    }

    /**
     * @return Centro[]
     */
    public function ordenZona(): array
    {
     $em = $this->getEntityManager();
     $query = $em->createQuery('SELECT a FROM App:Centro a ORDER BY a.zona ASC');
     return $query->getResult();
    }
}
