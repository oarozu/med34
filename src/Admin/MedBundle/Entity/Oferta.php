<?php
namespace Admin\MedBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="oferta", uniqueConstraints={@ORM\UniqueConstraint(columns={"curso_id", "periodo"})})
 * @ORM\Entity(repositoryClass="Admin\MedBundle\Entity\ofertalRepository")
 */
class Oferta{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
 */
 protected $id;

/**
* @var Curso
* @ORM\ManyToOne(targetEntity="AppBundle\Entity\Curso", inversedBy="oferta")
* @ORM\JoinColumn(name="curso_id", referencedColumnName="id", nullable=false)
*/
protected $curso;

/**
* @var Director
* @ORM\ManyToOne(targetEntity="AppBundle\Entity\Docente", inversedBy="director")
* @ORM\JoinColumn(name="director_id", referencedColumnName="id", nullable=false)
*/
protected $director;


/**
 * @ORM\Column(type="integer", nullable=false)
 */
protected $periodo;


/**
* @ORM\OneToMany(targetEntity="Admin\MedBundle\Entity\Tutor", mappedBy="oferta")
*/
protected $tutores;

    /**
    * @ORM\OneToOne(targetEntity="Admin\MedBundle\Entity\coevalDirector", mappedBy="oferta")
    */
   protected $coeval;

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
     * Set periodo
     *
     * @param integer $periodo
     * @return Oferta
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
     * Set curso
     *
     * @param \AppBundle\Entity\Curso $curso
     * @return Oferta
     */
    public function setCurso(\AppBundle\Entity\Curso $curso)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return \AppBundle\Entity\Curso
     */
    public function getCurso()
    {
        return $this->curso;
    }

    /**
     * Set director
     *
     * @param \AppBundle\Entity\Docente $director
     * @return Oferta
     */
    public function setDirector(\AppBundle\Entity\Docente $director)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return \AppBundle\Entity\Docente
     */
    public function getDirector()
    {
        return $this->director;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tutores = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tutores
     *
     * @param \Admin\MedBundle\Entity\Tutor $tutores
     * @return Oferta
     */
    public function addTutore(\Admin\MedBundle\Entity\Tutor $tutores)
    {
        $this->tutores[] = $tutores;

        return $this;
    }

    /**
     * Remove tutores
     *
     * @param \Admin\MedBundle\Entity\Tutor $tutores
     */
    public function removeTutore(\Admin\MedBundle\Entity\Tutor $tutores)
    {
        $this->tutores->removeElement($tutores);
    }

    /**
     * Get tutores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTutores()
    {
        return $this->tutores;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Oferta
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set coeval
     *
     * @param \Admin\MedBundle\Entity\coevalDirector $coeval
     * @return Oferta
     */
    public function setCoeval(\Admin\MedBundle\Entity\coevalDirector $coeval = null)
    {
        $this->coeval = $coeval;

        return $this;
    }

    /**
     * Get coeval
     *
     * @return \Admin\MedBundle\Entity\coevalDirector
     */
    public function getCoeval()
    {
        return $this->coeval;
    }
}
