<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="plangestion_pdf")
 * @ORM\Entity(repositoryClass="pdfPlangRepository")
 * @ORM\HasLifecycleCallbacks
 */
class pdfPlang
{
    protected $plan_id = 0;
    protected $periodo = 0;

    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length( max = "255" )
     */

    protected $path;


    /**
     * @Assert\File(
     *     maxSize = "3000000",
     *     maxSizeMessage = "El tamaño máximo permitido es de 3MB",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Tipo de archivo no válido, Solo se permite en formato PDF"
     * )
     */
    private $file;


    public function __construct($id, $periodo)
    {
        $this->plan_id = $id;
        $this->id = $id;
        $this->periodo = $periodo;
    }

    /******* Logica Archivos**/

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : '/pdfplang/' . $this->path;
    }

    protected function getUploadRootDir()
    {
        return 'home/evaluacion.docente/public_html/releases/appmed/public/repo/' . $this->getUploadDir();
    }


    protected function getUploadDir()
    {
        return 'repo/'. $this->periodo.'/pdfplang';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
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
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $filename = 'pdfplang-' . $this->plan_id;
            $this->path = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
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
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }


    /**
     * Set path
     *
     * @param string $path
     * @return pdfPlang
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
     * Set id
     * @param integer $id
     * @return pdfPlang
     */
    public function SetId($id)
    {
        $this->id = $id;
        return $this;
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
