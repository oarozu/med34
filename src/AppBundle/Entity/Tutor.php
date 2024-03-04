<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tutor", uniqueConstraints={@ORM\UniqueConstraint(columns={"docente_id", "oferta_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\tutorlRepository")
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
* @ORM\ManyToOne(targetEntity="AppBundle\Entity\Docente", inversedBy="tutoria")
* @ORM\JoinColumn(name="docente_id", referencedColumnName="id", nullable=false)
*/
protected $docente;

/**
* @var Oferta
* @ORM\ManyToOne(targetEntity="AppBundle\Entity\Oferta", inversedBy="tutores")
* @ORM\JoinColumn(name="oferta_id", referencedColumnName="id", nullable=false)
*/
protected $oferta;

/**
 * @ORM\OneToOne(targetEntity="AppBundle\Entity\redTutores", mappedBy="id")
 */
protected $coevaldirector;


/**
 * @ORM\OneToOne(targetEntity="AppBundle\Entity\coevalTutor", mappedBy="tutor")
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
     * @param \AppBundle\Entity\Docente $docente
     * @return Tutor
     */
    public function setDocente(\AppBundle\Entity\Docente $docente)
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
     * Set coevaldirector
     *
     * @param \AppBundle\Entity\redTurores $coevaldirector
     * @return Tutor
     */
    public function setCoevaldirector(\AppBundle\Entity\redTutores $coevaldirector = null)
    {
        $this->coevaldirector = $coevaldirector;

        return $this;
    }

    /**
     * Get coevaldirector
     *
     * @return \AppBundle\Entity\redTutores
     */
    public function getCoevaldirector()
    {
        return $this->coevaldirector;
    }

    /**
     * Set coevaltutor
     *
     * @param \AppBundle\Entity\coevalTutor $coevaltutor
     * @return Tutor
     */
    public function setCoevaltutor(\AppBundle\Entity\coevalTutor $coevaltutor = null)
    {
        $this->coevaltutor = $coevaltutor;

        return $this;
    }

    /**
     * Get coevaltutor
     *
     * @return \AppBundle\Entity\coevalTutor
     */
    public function getCoevaltutor()
    {
        return $this->coevaltutor;
    }

    /**
     * Set oferta
     *
     * @param \AppBundle\Entity\Oferta $oferta
     * @return Tutor
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
