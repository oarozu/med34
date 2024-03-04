<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="terna", uniqueConstraints={@ORM\UniqueConstraint(columns={"docente_id", "escuela_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ternaRepository")
 */
class Terna{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
 */
 protected $id;

/**
* @var Docente
* @ORM\ManyToOne(targetEntity="AppBundle\Entity\Docente", inversedBy="ternado")
* @ORM\JoinColumn(name="docente_id", referencedColumnName="id", nullable=false)
*/
protected $docente;

/**
* @var Escuela
* @ORM\ManyToOne(targetEntity="AppBundle\Entity\Escuela", inversedBy="ternados")
* @ORM\JoinColumn(name="escuela_id", referencedColumnName="id", nullable=false)
*/
protected $escuela;


/**
* @var Periodoe
* @ORM\ManyToOne(targetEntity="AppBundle\Entity\Periodoe")
* @ORM\JoinColumn(name="periodo_id", referencedColumnName="id")
*/
protected $periodo;


/**
* @ORM\OneToMany(targetEntity="AppBundle\Entity\coevalPares", mappedBy="evaluador")
*/
protected $evaluacion;


 /**
  * @ORM\Column(type="smallint", nullable=false)
  */
protected $principal;

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
     * Set docente
     *
     * @param \AppBundle\Entity\Docente $docente
     * @return Terna
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
     * Set escuela
     *
     * @param \AppBundle\Entity\Escuela $escuela
     * @return Terna
     */
    public function setEscuela(\AppBundle\Entity\Escuela $escuela)
    {
        $this->escuela = $escuela;

        return $this;
    }

    /**
     * Get escuela
     *
     * @return \AppBundle\Entity\Escuela
     */
    public function getEscuela()
    {
        return $this->escuela;
    }

    /**
     * Set principal
     *
     * @param integer $principal
     * @return Terna
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;

        return $this;
    }

    /**
     * Get principal
     *
     * @return integer
     */
    public function getPrincipal()
    {
        return $this->principal;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->evaluados = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add evaluados
     *
     * @param \AppBundle\Entity\coevalPares $evaluados
     * @return Terna
     */
    public function addEvaluado(\AppBundle\Entity\coevalPares $evaluados)
    {
        $this->evaluados[] = $evaluados;

        return $this;
    }


    /**
     * Add evaluacion
     *
     * @param \AppBundle\Entity\coevalPares $evaluacion
     * @return Terna
     */
    public function addEvaluacion(\AppBundle\Entity\coevalPares $evaluacion)
    {
        $this->evaluacion[] = $evaluacion;

        return $this;
    }

    /**
     * Remove evaluacion
     *
     * @param \AppBundle\Entity\coevalPares $evaluacion
     */
    public function removeEvaluacion(\AppBundle\Entity\coevalPares $evaluacion)
    {
        $this->evaluacion->removeElement($evaluacion);
    }

    /**
     * Get evaluacion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvaluacion()
    {
        return $this->evaluacion;
    }

    /**
     * Set periodo
     *
     * @param \AppBundle\Entity\Periodoe $periodo
     * @return Terna
     */
    public function setPeriodo(\AppBundle\Entity\Periodoe $periodo = null)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return \AppBundle\Entity\Periodoe
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }
}
