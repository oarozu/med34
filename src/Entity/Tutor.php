<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tutor", uniqueConstraints={@ORM\UniqueConstraint(columns={"docente_id", "oferta_id"})})
 * @ORM\Entity(repositoryClass="tutorlRepository")
 */
class Tutor{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
 */
 protected $id;

/**
 * @ORM\Column(type="integer", nullable=true)
 */
protected $estudiantes;


/**
* @var Docente
* @ORM\ManyToOne(targetEntity="App\Entity\Docente", inversedBy="tutoria")
* @ORM\JoinColumn(name="docente_id", referencedColumnName="id", nullable=false)
*/
protected $docente;

/**
* @var Oferta
* @ORM\ManyToOne(targetEntity="App\Entity\Oferta", inversedBy="tutores")
* @ORM\JoinColumn(name="oferta_id", referencedColumnName="id", nullable=false)
*/
protected $oferta;

/**
 * @ORM\OneToOne(targetEntity="App\Entity\redTutores", mappedBy="id")
 */
protected $coevaldirector;


/**
 * @ORM\OneToOne(targetEntity="App\Entity\coevalTutor", mappedBy="tutor")
 */
protected $coevaltutor;


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
     * Set estudiantes
     *
     * @param integer $estudiantes
     * @return Tutor
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
     * Set observaciones
     *
     * @param string $observaciones
     * @return Tutor
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
     * Set docente
     *
     * @param \App\Entity\Docente $docente
     * @return Tutor
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
     * Set coevaldirector
     *
     * @param \App\Entity\redTurores $coevaldirector
     * @return Tutor
     */
    public function setCoevaldirector(\App\Entity\redTutores $coevaldirector = null)
    {
        $this->coevaldirector = $coevaldirector;

        return $this;
    }

    /**
     * Get coevaldirector
     *
     * @return \App\Entity\redTutores
     */
    public function getCoevaldirector()
    {
        return $this->coevaldirector;
    }

    /**
     * Set coevaltutor
     *
     * @param \App\Entity\coevalTutor $coevaltutor
     * @return Tutor
     */
    public function setCoevaltutor(\App\Entity\coevalTutor $coevaltutor = null)
    {
        $this->coevaltutor = $coevaltutor;

        return $this;
    }

    /**
     * Get coevaltutor
     *
     * @return \App\Entity\coevalTutor
     */
    public function getCoevaltutor()
    {
        return $this->coevaltutor;
    }

    /**
     * Set oferta
     *
     * @param \App\Entity\Oferta $oferta
     * @return Tutor
     */
    public function setOferta(\App\Entity\Oferta $oferta)
    {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta
     *
     * @return \App\Entity\Oferta
     */
    public function getOferta()
    {
        return $this->oferta;
    }
}
