<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * actividadplanglRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class actividadplanglRepository extends EntityRepository
{
   public function ordenado($id){
    $em = $this->getEntityManager();
    // $centros = $em->getRepository('AppBundle:Centro')->findAll();
    $query = $em->createQuery('SELECT a FROM AppBundle:Actividadplang a JOIN a.actividadrol b WHERE b.plang = :id ORDER BY b.rol, b.id ASC');
    $query->setParameter('id', $id);
    $actividades = $query->getResult();
    return $actividades;
  }
}