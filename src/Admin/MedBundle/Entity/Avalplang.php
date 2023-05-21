<?php
namespace Admin\MedBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="aval_plangestion")
 * @ORM\Entity(repositoryClass="Admin\MedBundle\Entity\AvalplangRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Avalplang{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
     */
 protected $id;


  /**
  * @ORM\Column(type="string", length=5)
  */
protected $perfil;

  /**
  * @ORM\Column(type="smallint")
  */
protected $periodo;

 /**
  * @ORM\Column(type="text", length=1024, nullable=true)
  *
  */
protected $observaciones;

 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
protected $fecha_aval;

 /**
  * @ORM\Column(type="smallint", nullable=true)
  */
protected $avalado;

 /**
    * @var Plan
    * @ORM\ManyToOne(targetEntity="Admin\MedBundle\Entity\Plangestion", inversedBy="avales")
    * @ORM\JoinColumn(name="plangestion_id", referencedColumnName="id",
    * nullable=false
    * )
    */
protected $plan;

/**
    * @var User
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id",
    * nullable=false
    * )
    */
protected $user;


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
     * Set perfil
     *
     * @param string $perfil
     * @return Avalplang
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get perfil
     *
     * @return string
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Avalplang
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
     * Set fecha_aval
     *
     * @param \DateTime $fechaAval
     * @return Avalplang
     */
    public function setFechaAval($fechaAval)
    {
        $this->fecha_aval = $fechaAval;

        return $this;
    }

    /**
     * Get fecha_aval
     *
     * @return \DateTime
     */
    public function getFechaAval()
    {
        return $this->fecha_aval;
    }

    /**
     * Set avalado
     *
     * @param integer $avalado
     * @return Avalplang
     */
    public function setAvalado($avalado)
    {
        $this->avalado = $avalado;

        return $this;
    }

    /**
     * Get avalado
     *
     * @return integer
     */
    public function getAvalado()
    {
        return $this->avalado;
    }

     /**
     * Get avalado
     *
     * @return integer
     */
    public function getEstado()
    {
        if ($this->avalado == 1){
        return 'Aprobado';
        }
        else if ($this->avalado == 2){
        return 'No Aprobado';
        }
        else{
        return '';
        }
    }

    /**
     * Set plan
     *
     * @param \Admin\MedBundle\Entity\Plangestion $plan
     * @return Avalplang
     */
    public function setPlan(\Admin\MedBundle\Entity\Plangestion $plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return \Admin\MedBundle\Entity\Plangestion
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Avalplang
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set periodo
     *
     * @param integer $periodo
     * @return Avalplang
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return integer
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }
}
