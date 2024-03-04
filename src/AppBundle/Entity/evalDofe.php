<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="eval_dofe")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\evalDofeRepository")
 */
class evalDofe {

    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @var RedDofe
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RedDofe")
     * @ORM\JoinColumn(name="evaluacion_id", referencedColumnName="id", nullable=false)
     */
    protected $evaluacion;

    /**
     * @var Actividad
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Actividadrol")
     * @ORM\JoinColumn(name="actividad_id", referencedColumnName="id", nullable=false)
     */
    protected $actividad;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $calificacion;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set evaluacion
     *
     * @param \AppBundle\Entity\RedDofe $evaluacion
     * @return evalDofe
     */
    public function setEvaluacion(\AppBundle\Entity\RedDofe $evaluacion)
    {
        $this->evaluacion = $evaluacion;

        return $this;
    }

    /**
     * Get evaluacion
     *
     * @return \AppBundle\Entity\RedDofe
     */
    public function getEvaluacion()
    {
        return $this->evaluacion;
    }

    /**
     * Set actividad
     *
     * @param \AppBundle\Entity\Actividadrol $actividad
     * @return evalDofe
     */
    public function setActividad(\AppBundle\Entity\Actividadrol $actividad)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad
     *
     * @return \AppBundle\Entity\Actividadrol
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set calificacion
     *
     * @param integer $calificacion
     * @return evalDofe
     */
    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;

        return $this;
    }

    /**
     * Get calificacion
     *
     * @return integer
     */
    public function getCalificacion()
    {
        return $this->calificacion;
    }
}
