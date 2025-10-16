<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="periodoa")
 * @ORM\Entity(repositoryClass="periodoalRepository")
 */
class Periodoa{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 */
 protected $id;

/**
 * @ORM\Column(type="string", length=255)
 * @Assert\NotBlank()
 */
protected $nombre;


/**
 * @ORM\Column(type="datetime", nullable=false)
 */
protected $fechainicio;


/**
 * @ORM\Column(type="datetime", nullable=false)
 */
protected $fechafin;

/**
* @ORM\Column(type="integer", nullable=false)
*/
protected $semanas;

/**
 * @ORM\Column(type="string", nullable=true)
 */
protected $observaciones;


      /**
     * @var Periodoe
     * @ORM\ManyToOne(targetEntity="App\Entity\Periodoe", inversedBy="periodoa")
     * @ORM\JoinColumn(name="periodoe_id", referencedColumnName="id",
     * nullable=true
     * )
     */
protected $periodoe;


    /**
     * Set id
     *
     * @param integer $id
     * @return Periodoa
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set nombre
     *
     * @param string $nombre
     * @return Periodoa
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fechainicio
     *
     * @param \DateTime $fechainicio
     * @return Periodoa
     */
    public function setFechainicio($fechainicio)
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    /**
     * Get fechainicio
     *
     * @return \DateTime
     */
    public function getFechainicio()
    {
        return $this->fechainicio;
    }

    /**
     * Set fechafin
     *
     * @param \DateTime $fechafin
     * @return Periodoa
     */
    public function setFechafin($fechafin)
    {
        $this->fechafin = $fechafin;

        return $this;
    }

    /**
     * Get fechafin
     *
     * @return \DateTime
     */
    public function getFechafin()
    {
        return $this->fechafin;
    }

    /**
     * Set semanas
     *
     * @param integer $semanas
     * @return Periodoa
     */
    public function setSemanas($semanas)
    {
        $this->semanas = $semanas;

        return $this;
    }

    /**
     * Get semanas
     *
     * @return integer
     */
    public function getSemanas()
    {
        return $this->semanas;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Periodoa
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set periodoe
     *
     * @param \App\Entity\Periodoe $periodoe
     * @return Periodoa
     */
    public function setPeriodoe(\App\Entity\Periodoe $periodoe)
    {
        $this->periodoe = $periodoe;

        return $this;
    }

    /**
     * Get periodoe
     *
     * @return \App\Entity\Periodoe
     */
    public function getPeriodoe()
    {
        return $this->periodoe;
    }
}
