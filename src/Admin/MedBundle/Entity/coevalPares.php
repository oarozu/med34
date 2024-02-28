<?php
namespace Admin\MedBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="coeval_pares")
 * @ORM\Entity(repositoryClass="Admin\MedBundle\Entity\coevalParesRepository")
 */
class coevalPares{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
 */
private $id;


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
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f18;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f19;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f20;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f21;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f22;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f23;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f24;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f25;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f26;
 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f27;

 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f28;

 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f29;

 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $f30;

 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
protected $fecha;
 /**
  * @ORM\Column(type="string", length=512, nullable=true)
  */
protected $observaciones;

 /**
    * @var Evaluado
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Docente", inversedBy="coevaldepar")
    * @ORM\JoinColumn(name="evaluado_id", referencedColumnName="id",
    * nullable=false
    * )
    */
protected $evaluado;

 /**
    * @var Evaluador
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Terna", inversedBy="evaluacion")
    * @ORM\JoinColumn(name="evaluador_id", referencedColumnName="id",
    * nullable=false
    * )
    */
protected $evaluador;

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
     * Set f0
     *
     * @param string $f0
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * @return coevalPares
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
     * Set f16
     *
     * @param integer $f16
     * @return coevalPares
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
     * @return coevalPares
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
     * Set f18
     *
     * @param integer $f18
     * @return coevalPares
     */
    public function setF18($f18)
    {
        $this->f18 = $f18;

        return $this;
    }

    /**
     * Get f18
     *
     * @return integer
     */
    public function getF18()
    {
        return $this->f18;
    }

    /**
     * Set f19
     *
     * @param integer $f19
     * @return coevalPares
     */
    public function setF19($f19)
    {
        $this->f19 = $f19;

        return $this;
    }

    /**
     * Get f19
     *
     * @return integer
     */
    public function getF19()
    {
        return $this->f19;
    }

    /**
     * Set f20
     *
     * @param integer $f20
     * @return coevalPares
     */
    public function setF20($f20)
    {
        $this->f20 = $f20;

        return $this;
    }

    /**
     * Get f20
     *
     * @return integer
     */
    public function getF20()
    {
        return $this->f20;
    }

    /**
     * Set f21
     *
     * @param integer $f21
     * @return coevalPares
     */
    public function setF21($f21)
    {
        $this->f21 = $f21;

        return $this;
    }

    /**
     * Get f21
     *
     * @return integer
     */
    public function getF21()
    {
        return $this->f21;
    }

    /**
     * Set f22
     *
     * @param integer $f22
     * @return coevalPares
     */
    public function setF22($f22)
    {
        $this->f22 = $f22;

        return $this;
    }

    /**
     * Get f22
     *
     * @return integer
     */
    public function getF22()
    {
        return $this->f22;
    }

    /**
     * Set f23
     *
     * @param integer $f23
     * @return coevalPares
     */
    public function setF23($f23)
    {
        $this->f23 = $f23;

        return $this;
    }

    /**
     * Get f23
     *
     * @return integer
     */
    public function getF23()
    {
        return $this->f23;
    }

    /**
     * Set f24
     *
     * @param integer $f24
     * @return coevalPares
     */
    public function setF24($f24)
    {
        $this->f24 = $f24;

        return $this;
    }

    /**
     * Get f24
     *
     * @return integer
     */
    public function getF24()
    {
        return $this->f24;
    }

    /**
     * Set f25
     *
     * @param integer $f25
     * @return coevalPares
     */
    public function setF25($f25)
    {
        $this->f25 = $f25;

        return $this;
    }

    /**
     * Get f25
     *
     * @return integer
     */
    public function getF25()
    {
        return $this->f25;
    }

    /**
     * Set f26
     *
     * @param integer $f26
     * @return coevalPares
     */
    public function setF26($f26)
    {
        $this->f26 = $f26;

        return $this;
    }

    /**
     * Get f26
     *
     * @return integer
     */
    public function getF26()
    {
        return $this->f26;
    }

    /**
     * Set f27
     *
     * @param integer $f27
     * @return coevalPares
     */
    public function setF27($f27)
    {
        $this->f27 = $f27;

        return $this;
    }

    /**
     * Get f27
     *
     * @return integer
     */
    public function getF27()
    {
        return $this->f27;
    }

    /**
     * Set f28
     *
     * @param integer $f28
     * @return coevalPares
     */
    public function setF28($f28)
    {
        $this->f28 = $f28;

        return $this;
    }

    /**
     * Get f28
     *
     * @return integer
     */
    public function getF28()
    {
        return $this->f28;
    }

    /**
     * Set f29
     *
     * @param integer $f29
     * @return coevalPares
     */
    public function setF29($f29)
    {
        $this->f29 = $f29;

        return $this;
    }

    /**
     * Get f29
     *
     * @return integer
     */
    public function getF29()
    {
        return $this->f29;
    }

    /**
     * Set f30
     *
     * @param integer $f30
     * @return coevalPares
     */
    public function setF30($f30)
    {
        $this->f30 = $f30;

        return $this;
    }

    /**
     * Get f30
     *
     * @return integer
     */
    public function getF30()
    {
        return $this->f30;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return coevalPares
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
     * @return coevalPares
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
     * Set evaluado
     *
     * @param \AppBundle\Entity\Docente $evaluado
     * @return coevalPares
     */
    public function setEvaluado(\AppBundle\Entity\Docente $evaluado)
    {
        $this->evaluado = $evaluado;

        return $this;
    }

    /**
     * Get evaluado
     *
     * @return \AppBundle\Entity\Docente
     */
    public function getEvaluado()
    {
        return $this->evaluado;
    }

    /**
     * Set evaluador
     *
     * @param \AppBundle\Entity\Terna $evaluador
     * @return coevalPares
     */
    public function setEvaluador(\AppBundle\Entity\Terna $evaluador)
    {
        $this->evaluador = $evaluador;

        return $this;
    }

    /**
     * Get evaluador
     *
     * @return \AppBundle\Entity\Terna
     */
    public function getEvaluador()
    {
        return $this->evaluador;
    }
}
