<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="programa")
 * @ORM\Entity(repositoryClass="ProgramaRepository")
 */
class Programa{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="IDENTITY")
     */
 protected $id;

 /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
protected $nombre;

/**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank()
     */
protected $nivel;


     /**
     * @var Escuela
     * @ORM\ManyToOne(targetEntity="App\Entity\Escuela", inversedBy="programas")
     * @ORM\JoinColumn(name="escuela_id", referencedColumnName="id",
     * nullable=true
     * )
     */
protected $escuela;


    /**
     * @var Lider
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="lider_id", referencedColumnName="id",
     * nullable=true
     * )
     */
    protected $lider;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Docente", mappedBy="programa")
     */
    protected $docentes;


    /**
      * @ORM\OneToMany(targetEntity="App\Entity\Curso", mappedBy="programa")
      */
    protected $cursos;



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
     * Set nombre
     *
     * @param string $nombre
     * @return Programa
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
     * Set nivel
     *
     * @param string $nivel
     * @return Programa
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return string
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set escuela
     *
     * @param \App\Entity\Escuela $escuela
     * @return Programa
     */
    public function setEscuela(\App\Entity\Escuela $escuela)
    {
        $this->escuela = $escuela;

        return $this;
    }

    /**
     * Get escuela
     *
     * @return \App\Entity\Escuela
     */
    public function getEscuela()
    {
        return $this->escuela;

        }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Programa
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

        /**
     * Get lista para menu
     *
     * @return string
     */
    public function getLista()
    {
        if ($this->getEscuela()){
          return $this->getEscuela()->getSigla().'-'.$this->nombre;
        }
      else{
       return 'Sin-'.$this->nombre;
      }

    }

    /**
     * Set lider
     *
     * @param Entity\User $lider
     * @return Programa
     */
    public function setLider(\App\Entity\User $lider)
    {
        $this->lider = $lider;

        return $this;
    }

    /**
     * Get lider
     *
     * @return \App\Entity\User
     */
    public function getLider()
    {
        return $this->lider;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->docentes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add docentes
     *
     * @param \App\Entity\Docente $docentes
     * @return Programa
     */
    public function addDocente(\App\Entity\Docente $docentes)
    {
        $this->docentes[] = $docentes;

        return $this;
    }

    /**
     * Remove docentes
     *
     * @param \App\Entity\Docente $docentes
     */
    public function removeDocente(\App\Entity\Docente $docentes)
    {
        $this->docentes->removeElement($docentes);
    }

    /**
     * Get docentes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocentes()
    {
        return $this->docentes;
    }

    /**
     * Add cursos
     *
     * @param \App\Entity\Curso $cursos
     * @return Programa
     */
    public function addCurso(\App\Entity\Curso $cursos)
    {
        $this->cursos[] = $cursos;

        return $this;
    }

    /**
     * Remove cursos
     *
     * @param \App\Entity\Curso $cursos
     */
    public function removeCurso(\App\Entity\Curso $cursos)
    {
        $this->cursos->removeElement($cursos);
    }

    /**
     * Get cursos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursos()
    {
        return $this->cursos;
    }

    /**
     * Set coeval
     *
     * @param \App\Entity\coevalLider $coeval
     * @return Programa
     */
    public function setCoeval(\App\Entity\coevalLider $coeval)
    {
        $this->coeval = $coeval;

        return $this;
    }

    /**
     * Get coeval
     *
     * @return \App\Entity\coevalLider
     */
    public function getCoeval()
    {
        return $this->coeval;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->id.' - '.$this->nombre;
    }
}
