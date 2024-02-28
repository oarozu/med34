<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="curso")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CursoRepository")
 */
class Curso{

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
     * @ORM\Column(type="string", length=25)
     */
protected $tipologia;

/**
     * @ORM\Column(type="integer", length=2)
     */
protected $creditos;

/**
     * @ORM\Column(type="string", length=10)
     */
protected $escuela;


      /**
     * @var Programa
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Programa", inversedBy="cursos")
     * @ORM\JoinColumn(name="programa_id", referencedColumnName="id",
     * nullable=true
     * )
     */
protected $programa;



   /**
* @ORM\OneToMany(targetEntity="Admin\MedBundle\Entity\Oferta", mappedBy="curso")
*/
protected $oferta;

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
     * @return Curso
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
     * @return Curso
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
     * Set tipologia
     *
     * @param string $tipologia
     * @return Curso
     */
    public function setTipologia($tipologia)
    {
        $this->tipologia = $tipologia;

        return $this;
    }

    /**
     * Get tipologia
     *
     * @return string
     */
    public function getTipologia()
    {
        return $this->tipologia;
    }

    /**
     * Set creditos
     *
     * @param integer $creditos
     * @return Curso
     */
    public function setCreditos($creditos)
    {
        $this->creditos = $creditos;

        return $this;
    }

    /**
     * Get creditos
     *
     * @return integer
     */
    public function getCreditos()
    {
        return $this->creditos;
    }

    /**
     * Set escuela
     *
     * @param string $escuela
     * @return Curso
     */
    public function setEscuela($escuela)
    {
        $this->escuela = $escuela;

        return $this;
    }

    /**
     * Get escuela
     *
     * @return string
     */
    public function getEscuela()
    {
        return $this->escuela;
    }

    /**
     * Set Programa
     *
     * @param \AppBundle\Entity\Programa $programa
     * @return Curso
     */
    public function setPrograma(\AppBundle\Entity\Programa $programa = null)
    {
        $this->programa = $programa;

        return $this;
    }

    /**
     * Get Programa
     *
     * @return \AppBundle\Entity\Programa
     */
    public function getPrograma()
    {
        return $this->programa;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->docentes = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add oferta
     *
     * @param \Admin\MedBundle\Entity\Oferta $oferta
     * @return Curso
     */
    public function addOfertum(\Admin\MedBundle\Entity\Oferta $oferta)
    {
        $this->oferta[] = $oferta;

        return $this;
    }

    /**
     * Remove oferta
     *
     * @param \Admin\MedBundle\Entity\Oferta $oferta
     */
    public function removeOfertum(\Admin\MedBundle\Entity\Oferta $oferta)
    {
        $this->oferta->removeElement($oferta);
    }

    /**
     * Get oferta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOferta()
    {
        return $this->oferta;
    }
}
