<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="hetero_curso_full")
 */
class Heterocursosfull
{

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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * @return mixed
     */
    public function getCursoId()
    {
        return $this->curso_id;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return mixed
     */
    public function getSemestre()
    {
        return $this->semestre;
    }

    /**
     * @return mixed
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * @return mixed
     */
    public function getEstudiantes()
    {
        return $this->estudiantes;
    }

    /**
     * @return mixed
     */
    public function getCompetencia1()
    {
        return $this->competencia1;
    }

    /**
     * @return mixed
     */
    public function getCompetencia2()
    {
        return $this->competencia2;
    }

    /**
     * @return mixed
     */
    public function getCompetencia3()
    {
        return $this->competencia3;
    }

    /**
     * @return mixed
     */
    public function getCompetencia4()
    {
        return $this->competencia4;
    }

    /**
     * @return mixed
     */
    public function getCompetencia5()
    {
        return $this->competencia5;
    }

    /**
     * @return mixed
     */
    public function getCompetencia6()
    {
        return $this->competencia6;
    }

    /**
     * @return mixed
     */
    public function getCalificacion()
    {
        return $this->calificacion;
    }


}
