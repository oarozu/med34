<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="periodoe")
 * @ORM\Entity(repositoryClass="periodoeRepository")
 */
class Periodoe
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    protected $id;

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
    protected $dias;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $observaciones;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cronograma;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $auto;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $year;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $peraca;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Periodoa", mappedBy="periodoe")
     */
    protected $periodoa;


    /**
     * Set id
     *
     * @param integer $id
     * @return Periodoe
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
     * Set fechainicio
     *
     * @param \DateTime $fechainicio
     * @return Periodoe
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
     * @return Periodoe
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
     * Set dias
     *
     * @param integer $dias
     * @return Periodoe
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPeraca()
    {
        return $this->peraca;
    }


    /**
     * @param integer $peraca
     */
    public function setPeraca($peraca): void
    {
        $this->peraca = $peraca;
    }


    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return Periodoe
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Periodoe
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Periodoe
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set cronograma
     *
     * @param string $cronograma
     * @return Periodoe
     */
    public function setCronograma($cronograma)
    {
        $this->cronograma = $cronograma;

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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get cronograma
     *
     * @return string
     */
    public function getCronograma()
    {
        return $this->cronograma;
    }


    /**
     * Set auto
     *
     * @param integer $auto
     * @return Periodoe
     */
    public function setAuto($auto)
    {
        $this->auto = $auto;

        return $this;
    }

    /**
     * Get auto
     *
     * @return integer
     */
    public function getAuto()
    {
        return $this->auto;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->periodoa = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add periodoa
     *
     * @param \App\Entity\Periodoa $periodoa
     * @return Periodoe
     */
    public function addPeriodoa(\App\Entity\Periodoa $periodoa)
    {
        $this->periodoa[] = $periodoa;

        return $this;
    }

    /**
     * Remove periodoa
     *
     * @param \App\Entity\Periodoa $periodoa
     */
    public function removePeriodoa(\App\Entity\Periodoa $periodoa)
    {
        $this->periodoa->removeElement($periodoa);
    }

    /**
     * Get periodoa
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodoa()
    {
        return $this->periodoa;
    }
}
