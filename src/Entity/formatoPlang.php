<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @ORM\Entity
 * @ORM\Table(name="plangestion_formato")
 * @ORM\Entity(repositoryClass="formatoPlangRepository")
 * @ORM\HasLifecycleCallbacks
 */
class formatoPlang{


    /**
   * @var integer $id
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;


  /**
    * @var Plan
    * @ORM\OneToOne(targetEntity="App\Entity\Plangestion")
    * @ORM\JoinColumn(name="plangestion_id", referencedColumnName="id",
    * nullable=false
    * )
    */
protected $plan;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length( max = "255" )
     */

protected $path;


    /**
     * @Assert\File(
     *     maxSize = "2000000",
     *     maxSizeMessage = "El tamaño máximo permitido es de 2MB",
     *     mimeTypes = {"application/excel", "application/x-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"},
     *     mimeTypesMessage = "Tipo de archivo no válido, Solo se permite en formato Excel"
     * )
     */
 private $file;



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
        return 'uploads/'.$this->getPlan()->getDocente()->getPeriodo().'/formatoplang';
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
            $filename = 'formatoplang-'.$this->getPlan()->getDocente()->getId();
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
     * @return formatoPlang
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
     * Set plan
     *
     * @param \App\Entity\Plangestion $plan
     * @return formatoPlang
     */
    public function setPlan(\App\Entity\Plangestion $plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return \App\Entity\Plangestion
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
