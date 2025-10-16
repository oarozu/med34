<?php
namespace App\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

/**
  * @ORM\Table(name="hetero_estudiantes")
  * @ORM\Entity(repositoryClass="HeteroevalRepository")
  */
class Heteroeval{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 */
 private $id;


/**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $competencia1;

/**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $competencia2;

/**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $competencia3;

/**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $competencia4;

/**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $competencia5;

/**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $competencia6;

/**
  * @ORM\Column(type="decimal", scale=1, nullable=true)
  */
protected $calificacion;

 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
protected $fecha;


/**
 * @var Docente
 * @ORM\OneToOne(targetEntity="App\Entity\Docente", inversedBy="heteroeval")
 * @ORM\JoinColumn(name="id",referencedColumnName="id")
*/
 protected $docente;


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
     * Set competencia1
     *
     * @param string $competencia1
     * @return Heteroeval
     */
    public function setCompetencia1($competencia1)
    {
        $this->competencia1 = $competencia1;

        return $this;
    }

    /**
     * Get competencia1
     *
     * @return string
     */
    public function getCompetencia1()
    {
        return $this->competencia1;
    }

    /**
     * Set competencia2
     *
     * @param string $competencia2
     * @return Heteroeval
     */
    public function setCompetencia2($competencia2)
    {
        $this->competencia2 = $competencia2;

        return $this;
    }

    /**
     * Get competencia2
     *
     * @return string
     */
    public function getCompetencia2()
    {
        return $this->competencia2;
    }

    /**
     * Set competencia3
     *
     * @param string $competencia3
     * @return Heteroeval
     */
    public function setCompetencia3($competencia3)
    {
        $this->competencia3 = $competencia3;

        return $this;
    }

    /**
     * Get competencia3
     *
     * @return string
     */
    public function getCompetencia3()
    {
        return $this->competencia3;
    }

    /**
     * Set competencia4
     *
     * @param string $competencia4
     * @return Heteroeval
     */
    public function setCompetencia4($competencia4)
    {
        $this->competencia4 = $competencia4;

        return $this;
    }

    /**
     * Get competencia4
     *
     * @return string
     */
    public function getCompetencia4()
    {
        return $this->competencia4;
    }

    /**
     * Set competencia5
     *
     * @param string $competencia5
     * @return Heteroeval
     */
    public function setCompetencia5($competencia5)
    {
        $this->competencia5 = $competencia5;

        return $this;
    }

    /**
     * Get competencia5
     *
     * @return string
     */
    public function getCompetencia5()
    {
        return $this->competencia5;
    }

    /**
     * Set competencia6
     *
     * @param string $competencia6
     * @return Heteroeval
     */
    public function setCompetencia6($competencia6)
    {
        $this->competencia6 = $competencia6;

        return $this;
    }

    /**
     * Get competencia6
     *
     * @return string
     */
    public function getCompetencia6()
    {
        return $this->competencia6;
    }

    /**
     * Set calificacion
     *
     * @param string $calificacion
     * @return Heteroeval
     */
    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;

        return $this;
    }

    /**
     * Get calificacion
     *
     * @return string
     */
    public function getCalificacion()
    {
        return $this->calificacion;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Heteroeval
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
     * Set docente
     *
     * @param \App\Entity\Docente $docente
     * @return Heteroeval
     */
    public function setDocente(\App\Entity\Docente $docente)
    {
        $this->docente = $docente;

        return $this;
    }

    /**
     * Get docente
     *
     * @return \App\Entity\Docente
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
        $this->cursos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Heteroeval
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Add cursos
     *
     * @param \App\Entity\Heterocursos $cursos
     * @return Heteroeval
     */
    public function addCurso(\App\Entity\Heterocursos $cursos)
    {
        $this->cursos[] = $cursos;

        return $this;
    }
}
