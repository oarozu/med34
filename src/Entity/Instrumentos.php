<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

 /**
 * @ORM\Entity
 * @ORM\Table(name="instrumentos")
 */
class Instrumentos {

 /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fechainicio;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fechafin;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $estado;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $alcance;

    /**
     * @var Periodoe
     * @ORM\ManyToOne(targetEntity="App\Entity\Periodoe")
     * @ORM\JoinColumn(name="peridoe_id", referencedColumnName="id", nullable=false)
     */
    protected $periodoe;

    /**
     * @var Instrumento
     * @ORM\ManyToOne(targetEntity="App\Entity\Instrumento")
     * @ORM\JoinColumn(name="instrumento_id", referencedColumnName="id", nullable=false)
     */
    protected $instrumento;

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
     * @return Instrumentos
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
     * @return Instrumentos
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
     * Set estado
     *
     * @param integer $estado
     * @return Instrumentos
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set alcance
     *
     * @param string $alcance
     * @return Instrumentos
     */
    public function setAlcance($alcance)
    {
        $this->alcance = $alcance;

        return $this;
    }

    /**
     * Get alcance
     *
     * @return string
     */
    public function getAlcance()
    {
        return $this->alcance;
    }

    /**
     * Set periodoe
     *
     * @param \App\Entity\Periodoe $periodoe
     * @return Instrumentos
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

    /**
     * Set instrumento
     *
     * @param \App\Entity\Instrumento $instrumento
     * @return Instrumentos
     */
    public function setInstrumento(\App\Entity\Instrumento $instrumento)
    {
        $this->instrumento = $instrumento;

        return $this;
    }

    /**
     * Get instrumento
     *
     * @return \App\Entity\Instrumento
     */
    public function getInstrumento()
    {
        return $this->instrumento;
    }
}
