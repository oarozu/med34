<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Heteroeval
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HeteroevalRepository extends EntityRepository
{

    public function getPromedioescuela() {
    $connection = $this->getEntityManager()->getConnection();
    $q = "SELECT * FROM promHeteroescuela";
    $stmt = $connection->executeQuery($q);
    return $stmt->fetchAll();
}
}