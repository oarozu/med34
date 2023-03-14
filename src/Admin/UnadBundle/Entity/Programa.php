<?php
namespace Admin\UnadBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="programa")
 * @ORM\Entity(repositoryClass="Admin\UnadBundle\Entity\ProgramaRepository")
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
     * @ORM\ManyToOne(targetEntity="Admin\UnadBundle\Entity\Escuela", inversedBy="programas")
     * @ORM\JoinColumn(name="escuela_id", referencedColumnName="id",
     * nullable=true
     * )
     */
protected $escuela;


    /**
     * @var Lider
     * @ORM\ManyToOne(targetEntity="Admin\UserBundle\Entity\User", inversedBy="lider")
     * @ORM\JoinColumn(name="lider_id", referencedColumnName="id",
     * nullable=true
     * )
     */
    protected $lider;

    /**
     * @ORM\OneToMany(targetEntity="Admin\UnadBundle\Entity\Docente", mappedBy="programa")
     */
    protected $docentes;


    /**
      * @ORM\OneToMany(targetEntity="Admin\UnadBundle\Entity\Curso", mappedBy="programa")
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
     * @param \Admin\UnadBundle\Entity\Escuela $escuela
     * @return Programa
     */
    public function setEscuela(\Admin\UnadBundle\Entity\Escuela $escuela)
    {
        $this->escuela = $escuela;

        return $this;
    }

    /**
     * Get escuela
     *
     * @return \Admin\UnadBundle\Entity\Escuela
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
     * @param Admin\UserBundle\Entity\User $lider
     * @return Programa
     */
    public function setLider(\Admin\UserBundle\Entity\User $lider = null)
    {
        $this->lider = $lider;

        return $this;
    }

    /**
     * Get lider
     *
     * @return \Admin\UserBundle\Entity\User
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
     * @param \Admin\UnadBundle\Entity\Docente $docentes
     * @return Programa
     */
    public function addDocente(\Admin\UnadBundle\Entity\Docente $docentes)
    {
        $this->docentes[] = $docentes;

        return $this;
    }

    /**
     * Remove docentes
     *
     * @param \Admin\UnadBundle\Entity\Docente $docentes
     */
    public function removeDocente(\Admin\UnadBundle\Entity\Docente $docentes)
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
     * @param \Admin\UnadBundle\Entity\Curso $cursos
     * @return Programa
     */
    public function addCurso(\Admin\UnadBundle\Entity\Curso $cursos)
    {
        $this->cursos[] = $cursos;

        return $this;
    }

    /**
     * Remove cursos
     *
     * @param \Admin\UnadBundle\Entity\Curso $cursos
     */
    public function removeCurso(\Admin\UnadBundle\Entity\Curso $cursos)
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
     * @param \Admin\MedBundle\Entity\coevalLider $coeval
     * @return Programa
     */
    public function setCoeval(\Admin\MedBundle\Entity\coevalLider $coeval = null)
    {
        $this->coeval = $coeval;

        return $this;
    }

    /**
     * Get coeval
     *
     * @return \Admin\MedBundle\Entity\coevalLider
     */
    public function getCoeval()
    {
        return $this->coeval;
    }
}
