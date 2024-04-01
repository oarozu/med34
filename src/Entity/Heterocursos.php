<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="hetero_curso")
 * @ORM\Entity(repositoryClass="HeterocursosRepository")
 */
class Heterocursos{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
 */
private $id;

 /**
  * @ORM\Column(type="integer")
  */
protected $cedula;

/**
  * @ORM\Column(type="integer")
  */
protected $curso_id;

 /**
  * @ORM\Column(type="string", length=512, nullable=true)
  */
protected $nombre;

 /**
  * @ORM\Column(type="string", length=10, nullable=false)
  */
protected $semestre;

 /**
  * @ORM\Column(type="string", length=10, nullable=false)
  */
protected $periodo;

 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $estudiantes;

/**
  * @ORM\Column(type="decimal", scale=2, nullable=true)
  */
protected $competencia1;

/**
  * @ORM\Column(type="decimal", scale=2, nullable=true)
  */
protected $competencia2;

/**
  * @ORM\Column(type="decimal", scale=2, nullable=true)
  */
protected $competencia3;

/**
  * @ORM\Column(type="decimal", scale=2, nullable=true)
  */
protected $competencia4;

/**
  * @ORM\Column(type="decimal", scale=2, nullable=true)
  */
protected $competencia5;

/**
  * @ORM\Column(type="decimal", scale=2, nullable=true)
  */
protected $competencia6;

/**
  * @ORM\Column(type="decimal", scale=2, nullable=true)
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
     * Set curso_id
     *
     * @param integer $cursoId
     * @return Heterocursos
     */
    public function setCursoId($cursoId)
    {
        $this->curso_id = $cursoId;

        return $this;
    }

    /**
     * Get curso_id
     *
     * @return integer
     */
    public function getCursoId()
    {
        return $this->curso_id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Heterocursos
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
     * Set periodo
     *
     * @param string $periodo
     * @return Heterocursos
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return string
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set estudiantes
     *
     * @param integer $estudiantes
     * @return Heterocursos
     */
    public function setEstudiantes($estudiantes)
    {
        $this->estudiantes = $estudiantes;

        return $this;
    }

    /**
     * Get estudiantes
     *
     * @return integer
     */
    public function getEstudiantes()
    {
        return $this->estudiantes;
    }

    /**
     * Set competencia1
     *
     * @param string $competencia1
     * @return Heterocursos
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
     * @return Heterocursos
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
     * @return Heterocursos
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
     * @return Heterocursos
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
     * @return Heterocursos
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
     * @return Heterocursos
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
     * @return Heterocursos
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
     * Set cedula
     *
     * @param integer $cedula
     * @return Heterocursos
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return integer
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set semestre
     *
     * @param string $semestre
     * @return Heterocursos
     */
    public function setSemestre($semestre)
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * Get semestre
     *
     * @return string
     */
    public function getSemestre()
    {
        return $this->semestre;
    }
}
