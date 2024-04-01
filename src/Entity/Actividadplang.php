<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="actividad_plang")
 * @ORM\Entity(repositoryClass="actividadplanglRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Actividadplang{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
 */
 protected $id;

 /**
 * @ORM\Column(type="decimal", scale=1, nullable=true)
 */
protected $horas;

/**
* @ORM\Column(type="string", length=2500, nullable=true)
*/
protected $descripcion;

/**
* @ORM\Column(type="string", length=2500, nullable=true)
*/
protected $observaciones;

/**
 * @ORM\Column(type="float", scale=1, nullable=true)
 */
protected $autoevaluacion;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length( max = "255" )
     */

protected $path;

    /**
     * @Assert\File(
     *     maxSize = "4000000",
     *     maxSizeMessage = "El tamaño máximo permitido es de 4MB",
     *     mimeTypes = {"application/pdf", "application/x-pdf", "application/zip"},
     *     mimeTypesMessage = "Tipo de archivo no válido"
     * )
     */
 private $file;


/**
* @var Plang
* @ORM\ManyToOne(targetEntity="App\Entity\Plangestion", inversedBy="actividades")
* @ORM\JoinColumn(name="plan_id", referencedColumnName="id", nullable=false)
*/
protected $plang;


/**
* @var Actividad
* @ORM\ManyToOne(targetEntity="App\Entity\Actividadrol")
* @ORM\JoinColumn(name="actividad_id", referencedColumnName="id", nullable=false)
*/
protected $actividad;

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
     * Set horas
     *
     * @param float $horas
     * @return Actividadplang
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return float
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Actividadplang
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Actividadplang
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
     * Set autoevaluacion
     *
     * @param float $autoevaluacion
     * @return Actividadplang
     */
    public function setAutoevaluacion($autoevaluacion)
    {
        $this->autoevaluacion = $autoevaluacion;

        return $this;
    }

    /**
     * Get autoevaluacion
     *
     * @return float
     */
    public function getAutoevaluacion()
    {
        return $this->autoevaluacion;
    }

    /**
     * Set plang
     *
     * @param \App\Entity\Plangestion $plang
     * @return Actividadplang
     */
    public function setPlang(\App\Entity\Plangestion $plang)
    {
        $this->plang = $plang;

        return $this;
    }

    /**
     * Get plang
     *
     * @return \App\Entity\Plangestion
     */
    public function getPlang()
    {
        return $this->plang;
    }

    /**
     * Set actividad
     *
     * @param \App\Entity\Actividadrol $actividad
     * @return Actividadplang
     */
    public function setActividad(\App\Entity\Actividadrol $actividad)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad
     *
     * @return \App\Entity\Actividadrol
     */
    public function getActividad()
    {
        return $this->actividad;
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
        return 'uploads/'.$this->getPlang()->getDocente()->getPeriodo().'/plang/plan_'.$this->getPlang()->getDocente()->getId();

    }

    public function getWebPathRepo() {
        return null === $this->path
            ? null
            : $this->getUploadDirRepo() . '/' . $this->path;
    }

    protected function getUploadDirRepo() {
        return 'repo/'.$this->getPlang()->getDocente()->getPeriodo().'/plang/plan_'.$this->getPlang()->getDocente()->getId();

    }
      /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)     {
        $this->file = $file;
     //   $this->descripcion = 'adjunto';

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
            $filename = 'evidencia-'.$this->getId();
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

     /**
     * Get letras
     *
     * @return string
     */
    public function getLetras()
    {
        if ($this->autoevaluacion == null )
        return ' ';
        elseif ($this->autoevaluacion == 0)
        return 'No Aplica';
        elseif ($this->autoevaluacion == 1)
        return 'Nunca';
        elseif ($this->autoevaluacion == 2)
        return 'Casi Nunca';
        elseif ($this->autoevaluacion == 3)
        return 'Aveces';
        elseif ($this->autoevaluacion == 4)
        return 'Casi Siempre';
        elseif ($this->autoevaluacion == 5)
        return 'Siempre';

        else
         return $this->estado;
    }
}
