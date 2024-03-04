<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="acciones_planm")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AccionespmRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Accionespm{

 /**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
     */
 protected $id;


 /**
     * @ORM\Column(type="string", length=512)
     */
protected $oportunidad;

 /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
protected $causas;


 /**
     * @ORM\Column(type="string", length=512)
     */
protected $accion;

/**
  * @ORM\Column(type="date", nullable=true)
  */
protected $fecha_proyectada;

 /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
protected $observaciones;

/**
  * @ORM\Column(type="datetime", nullable=true)
  */
protected $fecha_cierre;


 /**
     * @ORM\Column(type="smallint", nullable=true)
     */
protected $estado;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length( max = "255" )
     */

protected $path;

    /**
     * @Assert\File(
     *     maxSize = "3000000",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Tipo de archivo no válido"
     * )
     */
 private $file;


/**
     * @var Plan
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Planmejoramiento", inversedBy="acciones")
     * @ORM\JoinColumn(name="plan_id", referencedColumnName="id",
     * nullable=false,
     * onDelete="CASCADE"
     * )
     */
protected $plan;


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
     * Set oportunidad
     *
     * @param string $oportunidad
     * @return Accionespm
     */
    public function setOportunidad($oportunidad)
    {
        $this->oportunidad = $oportunidad;

        return $this;
    }

    /**
     * Get oportunidad
     *
     * @return string
     */
    public function getOportunidad()
    {
        return $this->oportunidad;
    }

    /**
     * Set causas
     *
     * @param string $causas
     * @return Accionespm
     */
    public function setCausas($causas)
    {
        $this->causas = $causas;

        return $this;
    }

    /**
     * Get causas
     *
     * @return string
     */
    public function getCausas()
    {
        return $this->causas;
    }

    /**
     * Set accion
     *
     * @param string $accion
     * @return Accionespm
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return string
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set fecha_proyectada
     *
     * @param \DateTime $fechaProyectada
     * @return Accionespm
     */
    public function setFechaProyectada($fechaProyectada)
    {
        $this->fecha_proyectada = $fechaProyectada;

        return $this;
    }

    /**
     * Get fecha_proyectada
     *
     * @return \DateTime
     */
    public function getFechaProyectada()
    {
        return $this->fecha_proyectada;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Accionespm
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
     * Set fecha_cierre
     *
     * @param \DateTime $fechaCierre
     * @return Accionespm
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fecha_cierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fecha_cierre
     *
     * @return \DateTime
     */
    public function getFechaCierre()
    {
        return $this->fecha_cierre;
    }

    /**
     * Set plan
     *
     * @param \AppBundle\Entity\Planmejoramiento $plan
     * @return Accionespm
     */
    public function setPlan(\AppBundle\Entity\Planmejoramiento $plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return \AppBundle\Entity\Planmejoramiento
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return Accionespm
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer
     */
    public function getEstado()
    {
        return $this->estado;
    }

   /******* Logica Archivos**/

     public function getAbsolutePath() {
        return null === $this->path
                ? null
                : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath() {
        return null === $this->path
                ? null
                : $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'archivos/planm';
    }
      /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)     {
        $this->file = $file;
        $this->descripcion = 'adjunto';

        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()     {
        return $this->file;
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()     {
        if (null !== $this->getFile()) {
            // haz lo que quieras para generar un nombre único
            //$filename = sha1(uniqid(mt_rand(), true));
            $filename = $this->getPlan()->getId().'-'.$this->getId();
            $this->path = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()     {
        if (null === $this->getFile()) {
            return;
        }

        // si hay un error al mover el archivo, move() automáticamente
        // envía una excepción. This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

        /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }


    /**
     * Set path
     *
     * @param string $path
     * @return Accionespm
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
