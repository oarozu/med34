<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="coeval_director")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\coevalDirectorRepository")
 */
class coevalDirector{

 /**
  * @ORM\Id
 * @ORM\OneToOne(targetEntity="AppBundle\Entity\Oferta", inversedBy="coeval")
 * @ORM\JoinColumn(name="oferta_id",referencedColumnName="id")
 */
 protected $oferta;

/**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $f0;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f1;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f2;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f3;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f4;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f5;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f6;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f7;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f8;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f9;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f10;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f11;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f12;

 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f13;

 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f14;

 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f15;

 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f16;

 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f17;


 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
protected $fecha;
 /**
  * @ORM\Column(type="string", length=512, nullable=true)
  */
protected $observaciones;


     /**
     * Set f0
     *
     * @param string $f0
     * @return coevalDirector
     */
    public function setF0($f0)
    {
        $this->f0 = $f0;

        return $this;
    }

    /**
     * Get f0
     *
     * @return string
     */
    public function getF0()
    {
        return $this->f0;
    }

    /**
     * Set f1
     *
     * @param integer $f1
     * @return coevalDirector
     */
    public function setF1($f1)
    {
        $this->f1 = $f1;

        return $this;
    }

    /**
     * Get f1
     *
     * @return integer
     */
    public function getF1()
    {
        return $this->f1;
    }

    /**
     * Set f2
     *
     * @param integer $f2
     * @return coevalDirector
     */
    public function setF2($f2)
    {
        $this->f2 = $f2;

        return $this;
    }

    /**
     * Get f2
     *
     * @return integer
     */
    public function getF2()
    {
        return $this->f2;
    }

    /**
     * Set f3
     *
     * @param integer $f3
     * @return coevalDirector
     */
    public function setF3($f3)
    {
        $this->f3 = $f3;

        return $this;
    }

    /**
     * Get f3
     *
     * @return integer
     */
    public function getF3()
    {
        return $this->f3;
    }

    /**
     * Set f4
     *
     * @param integer $f4
     * @return coevalDirector
     */
    public function setF4($f4)
    {
        $this->f4 = $f4;

        return $this;
    }

    /**
     * Get f4
     *
     * @return integer
     */
    public function getF4()
    {
        return $this->f4;
    }

    /**
     * Set f5
     *
     * @param integer $f5
     * @return coevalDirector
     */
    public function setF5($f5)
    {
        $this->f5 = $f5;

        return $this;
    }

    /**
     * Get f5
     *
     * @return integer
     */
    public function getF5()
    {
        return $this->f5;
    }

    /**
     * Set f6
     *
     * @param integer $f6
     * @return coevalDirector
     */
    public function setF6($f6)
    {
        $this->f6 = $f6;

        return $this;
    }

    /**
     * Get f6
     *
     * @return integer
     */
    public function getF6()
    {
        return $this->f6;
    }

    /**
     * Set f7
     *
     * @param integer $f7
     * @return coevalDirector
     */
    public function setF7($f7)
    {
        $this->f7 = $f7;

        return $this;
    }

    /**
     * Get f7
     *
     * @return integer
     */
    public function getF7()
    {
        return $this->f7;
    }

    /**
     * Set f8
     *
     * @param integer $f8
     * @return coevalDirector
     */
    public function setF8($f8)
    {
        $this->f8 = $f8;

        return $this;
    }

    /**
     * Get f8
     *
     * @return integer
     */
    public function getF8()
    {
        return $this->f8;
    }

    /**
     * Set f9
     *
     * @param integer $f9
     * @return coevalDirector
     */
    public function setF9($f9)
    {
        $this->f9 = $f9;

        return $this;
    }

    /**
     * Get f9
     *
     * @return integer
     */
    public function getF9()
    {
        return $this->f9;
    }

    /**
     * Set f10
     *
     * @param integer $f10
     * @return coevalDirector
     */
    public function setF10($f10)
    {
        $this->f10 = $f10;

        return $this;
    }

    /**
     * Get f10
     *
     * @return integer
     */
    public function getF10()
    {
        return $this->f10;
    }

    /**
     * Set f11
     *
     * @param integer $f11
     * @return coevalDirector
     */
    public function setF11($f11)
    {
        $this->f11 = $f11;

        return $this;
    }

    /**
     * Get f11
     *
     * @return integer
     */
    public function getF11()
    {
        return $this->f11;
    }

    /**
     * Set f12
     *
     * @param integer $f12
     * @return coevalDirector
     */
    public function setF12($f12)
    {
        $this->f12 = $f12;

        return $this;
    }

    /**
     * Get f12
     *
     * @return integer
     */
    public function getF12()
    {
        return $this->f12;
    }

    /**
     * Set f13
     *
     * @param integer $f13
     * @return coevalDirector
     */
    public function setF13($f13)
    {
        $this->f13 = $f13;

        return $this;
    }

    /**
     * Get f13
     *
     * @return integer
     */
    public function getF13()
    {
        return $this->f13;
    }

    /**
     * Set f14
     *
     * @param integer $f14
     * @return coevalDirector
     */
    public function setF14($f14)
    {
        $this->f14 = $f14;

        return $this;
    }

    /**
     * Get f14
     *
     * @return integer
     */
    public function getF14()
    {
        return $this->f14;
    }

    /**
     * Set f15
     *
     * @param integer $f15
     * @return coevalDirector
     */
    public function setF15($f15)
    {
        $this->f15 = $f15;

        return $this;
    }

    /**
     * Get f15
     *
     * @return integer
     */
    public function getF15()
    {
        return $this->f15;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return coevalDirector
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
     * @return coevalDirector
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
     * Set f16
     *
     * @param integer $f16
     * @return coevalDirector
     */
    public function setF16($f16)
    {
        $this->f16 = $f16;

        return $this;
    }

    /**
     * Get f16
     *
     * @return integer
     */
    public function getF16()
    {
        return $this->f16;
    }

    /**
     * Set f17
     *
     * @param integer $f17
     * @return coevalDirector
     */
    public function setF17($f17)
    {
        $this->f17 = $f17;

        return $this;
    }

    /**
     * Get f17
     *
     * @return integer
     */
    public function getF17()
    {
        return $this->f17;
    }




    /**
     * Set oferta
     *
     * @param \AppBundle\Entity\Oferta $oferta
     * @return coevalDirector
     */
    public function setOferta(\AppBundle\Entity\Oferta $oferta)
    {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta
     *
     * @return \AppBundle\Entity\Oferta
     */
    public function getOferta()
    {
        return $this->oferta;
    }
}
