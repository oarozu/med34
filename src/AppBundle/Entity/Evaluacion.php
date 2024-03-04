<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="evaluacion")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EvaluacionRepository")
 */
class Evaluacion{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 */
 private $id;

 /**
 * @ORM\Column(type="decimal", scale=1, nullable=true)
 */
protected $final;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $auto;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $auto1;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $auto2;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $hetero;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $hetero1;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $hetero2;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $hetero3;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $co;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $co1;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $co2;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $co3;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $co4;

 /**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $co5;


 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
protected $fecha;

/**
* @ORM\Column(type="string", length=512, nullable=true)
*/
protected $observaciones;

/**
* @ORM\Column(type="text",  nullable=true)
*/
protected $aclaraciones;

/**
 * @var Docente
 * @ORM\OneToOne(targetEntity="AppBundle\Entity\Docente", inversedBy="evaluacion")
 * @ORM\JoinColumn(name="id",referencedColumnName="id")
*/
 protected $docente;

    /**
     * Set id
     *
     * @param integer $id
     * @return Evaluacion
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
     * Set final
     *
     * @param string $final
     * @return Evaluacion
     */
    public function setFinal($final)
    {
        $this->final = $final;

        return $this;
    }

    /**
     * Get final
     *
     * @return string
     */
    public function getFinal()
    {
        return $this->final;
    }

    /**
     * Set auto
     *
     * @param string $auto
     * @return Evaluacion
     */
    public function setAuto($auto)
    {
        $this->auto = $auto;

        return $this;
    }

    /**
     * Get auto
     *
     * @return string
     */
    public function getAuto()
    {
        return $this->auto;
    }

    /**
     * Set auto1
     *
     * @param string $auto1
     * @return Evaluacion
     */
    public function setAuto1($auto1)
    {
        $this->auto1 = $auto1;

        return $this;
    }

    /**
     * Get auto1
     *
     * @return string
     */
    public function getAuto1()
    {
        return $this->auto1;
    }

    /**
     * Set auto2
     *
     * @param string $auto2
     * @return Evaluacion
     */
    public function setAuto2($auto2)
    {
        $this->auto2 = $auto2;

        return $this;
    }

    /**
     * Get auto2
     *
     * @return string
     */
    public function getAuto2()
    {
        return $this->auto2;
    }

    /**
     * Set hetero
     *
     * @param string $hetero
     * @return Evaluacion
     */
    public function setHetero($hetero)
    {
        $this->hetero = $hetero;

        return $this;
    }

    /**
     * Get hetero
     *
     * @return string
     */
    public function getHetero()
    {
        return $this->hetero;
    }

    /**
     * Set hetero1
     *
     * @param string $hetero1
     * @return Evaluacion
     */
    public function setHetero1($hetero1)
    {
        $this->hetero1 = $hetero1;

        return $this;
    }

    /**
     * Get hetero1
     *
     * @return string
     */
    public function getHetero1()
    {
        return $this->hetero1;
    }

    /**
     * Set hetero2
     *
     * @param string $hetero2
     * @return Evaluacion
     */
    public function setHetero2($hetero2)
    {
        $this->hetero2 = $hetero2;

        return $this;
    }

    /**
     * Get hetero2
     *
     * @return string
     */
    public function getHetero2()
    {
        return $this->hetero2;
    }

    /**
     * Set hetero3
     *
     * @param string $hetero3
     * @return Evaluacion
     */
    public function setHetero3($hetero3)
    {
        $this->hetero3 = $hetero3;

        return $this;
    }

    /**
     * Get hetero3
     *
     * @return string
     */
    public function getHetero3()
    {
        return $this->hetero3;
    }


    /**
     * Set co
     *
     * @param string $co
     * @return Evaluacion
     */
    public function setCo($co)
    {
        $this->co = $co;

        return $this;
    }

    /**
     * Get co
     *
     * @return string
     */
    public function getCo()
    {
        return $this->co;
    }

    /**
     * Set co1
     *
     * @param string $co1
     * @return Evaluacion
     */
    public function setCo1($co1)
    {
        $this->co1 = $co1;

        return $this;
    }

    /**
     * Get co1
     *
     * @return string
     */
    public function getCo1()
    {
        return $this->co1;
    }

    /**
     * Set co2
     *
     * @param string $co2
     * @return Evaluacion
     */
    public function setCo2($co2)
    {
        $this->co2 = $co2;

        return $this;
    }

    /**
     * Get co2
     *
     * @return string
     */
    public function getCo2()
    {
        return $this->co2;
    }

    /**
     * Set co3
     *
     * @param string $co3
     * @return Evaluacion
     */
    public function setCo3($co3)
    {
        $this->co3 = $co3;

        return $this;
    }

    /**
     * Get co3
     *
     * @return string
     */
    public function getCo3()
    {
        return $this->co3;
    }

    /**
     * Set co4
     *
     * @param string $co4
     * @return Evaluacion
     */
    public function setCo4($co4)
    {
        $this->co4 = $co4;

        return $this;
    }

    /**
     * Get co4
     *
     * @return string
     */
    public function getCo4()
    {
        return $this->co4;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Evaluacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Evaluacion
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
     * Set aclaraciones
     *
     * @param string $aclaraciones
     * @return Evaluacion
     */
    public function setAclaraciones($aclaraciones)
    {
        $this->aclaraciones = $aclaraciones;

        return $this;
    }

    /**
     * Get aclaraciones
     *
     * @return string
     */
    public function getAclaraciones()
    {
        return $this->aclaraciones;
    }

    /**
     * Set docente
     *
     * @param \AppBundle\Entity\Docente $docente
     * @return Evaluacion
     */
    public function setDocente(\AppBundle\Entity\Docente $docente = null)
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
     * Set co5
     *
     * @param string $co5
     * @return Evaluacion
     */
    public function setCo5($co5)
    {
        $this->co5 = $co5;

        return $this;
    }

    /**
     * Get co5
     *
     * @return string
     */
    public function getCo5()
    {
        return $this->co5;
    }
}
