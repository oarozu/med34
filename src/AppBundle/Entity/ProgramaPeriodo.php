<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="programa_periodo")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ProgramaPeriodoRepository")
 */
class ProgramaPeriodo {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Programa
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Programa")
     * @ORM\JoinColumn(name="programa_id", referencedColumnName="id",
     * nullable=false
     * )
     */
    protected $programa;

    /**
     * @var Periodo
     * @ORM\ManyToOne(targetEntity="Admin\MedBundle\Entity\Periodoe")
     * @ORM\JoinColumn(name="periodo_id", referencedColumnName="id",
     * nullable=false
     * )
     */
    protected $periodo;

    /**
     * @var Lider
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Docente", inversedBy="lider")
     * @ORM\JoinColumn(name="lider_id", referencedColumnName="id",
     * nullable=true
     * )
     */
    protected $lider;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $observaciones;


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
     * Set observaciones
     *
     * @param string $observaciones
     * @return ProgramaPeriodo
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
     * Set programa
     *
     * @param \AppBundle\Entity\Programa $programa
     * @return ProgramaPeriodo
     */
    public function setPrograma(\AppBundle\Entity\Programa $programa)
    {
        $this->programa = $programa;

        return $this;
    }

    /**
     * Get programa
     *
     * @return \AppBundle\Entity\Programa
     */
    public function getPrograma()
    {
        return $this->programa;
    }

    /**
     * Set periodo
     *
     * @param \Admin\MedBundle\Entity\Periodoe $periodo
     * @return ProgramaPeriodo
     */
    public function setPeriodo(\Admin\MedBundle\Entity\Periodoe $periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return \Admin\MedBundle\Entity\Periodoe
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set lider
     *
     * @param \AppBundle\Entity\Docente $lider
     * @return ProgramaPeriodo
     */
    public function setLider(\AppBundle\Entity\Docente $lider = null)
    {
        $this->lider = $lider;

        return $this;
    }

    /**
     * Get lider
     *
     * @return \AppBundle\Entity\Docente
     */
    public function getLider()
    {
        return $this->lider;
    }
}
