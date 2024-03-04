<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="plan_mejoramiento")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PlanmejoramientoRepository")
 */
class Planmejoramiento{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
     */
 protected $id;

 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
protected $fecha_creacion;

 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
protected $fecha_cierre;

 /**
  * @ORM\Column(type="string", length=512, nullable=true)
  */
protected $observaciones;

/**
 * @ORM\Column(type="float", scale=2, nullable=true)
 */
protected $calificacion;

 /**
  * @ORM\Column(type="integer", nullable=true)
  */
protected $autorid;

/**
     * @var Docente
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Docente", inversedBy="planmejoramiento")
     * @ORM\JoinColumn(name="docente_id", referencedColumnName="id",
     * nullable=false
     * )
     */
protected $docente;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Accionespm", mappedBy="plan")
     */
    protected $acciones;

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
     * Set fecha_creacion
     *
     * @param \DateTime $fechaCreacion
     * @return Planmejoramiento
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fecha_creacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fecha_creacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    /**
     * Set fecha_cierre
     *
     * @param \DateTime $fechaCierre
     * @return Planmejoramiento
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fecha_cierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fecha_cierre
     *
     * @return \DateTime
     */
    public function getFechaCierre()
    {
        return $this->fecha_cierre;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Planmejoramiento
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
     * Set calificacion
     *
     * @param float $calificacion
     * @return Planmejoramiento
     */
    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;

        return $this;
    }

    /**
     * Get calificacion
     *
     * @return float
     */
    public function getCalificacion()
    {
        return $this->calificacion;
    }

    /**
     * Set docente
     *
     * @param \AppBundle\Entity\Docente $docente
     * @return Planmejoramiento
     */
    public function setDocente(\AppBundle\Entity\Docente $docente)
    {
        $this->docente = $docente;

        return $this;
    }

    /**
     * Get docente
     *
     * @return \AppBundle\Entity\Docente
     */
    public function getDocente()
    {
        return $this->docente;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->acciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add acciones
     *
     * @param \AppBundle\Entity\Accionespm $acciones
     * @return Planmejoramiento
     */
    public function addAccione(\AppBundle\Entity\Accionespm $acciones)
    {
        $this->acciones[] = $acciones;

        return $this;
    }

    /**
     * Remove acciones
     *
     * @param \AppBundle\Entity\Accionespm $acciones
     */
    public function removeAccione(\AppBundle\Entity\Accionespm $acciones)
    {
        $this->acciones->removeElement($acciones);
    }

    /**
     * Get acciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcciones()
    {
        return $this->acciones;
    }

    /**
     * Set autorid
     *
     * @param integer $autorid
     * @return Planmejoramiento
     */
    public function setAutorid($autorid)
    {
        $this->autorid = $autorid;

        return $this;
    }

    /**
     * Get autorid
     *
     * @return integer
     */
    public function getAutorid()
    {
        return $this->autorid;
    }
}
