<?php
namespace Admin\UnadBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="centro")
 * @ORM\Entity(repositoryClass="Admin\UnadBundle\Entity\CentroRepository")
 */

class Centro{

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
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     */
protected $tipo;

      /**
     * @var Director
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="directorcentro")
     * @ORM\JoinColumn(name="director_id", referencedColumnName="id",
     * nullable=true
     * )
     */
protected $director;

    /**
     * @Assert\NotBlank()
     */
    protected $cedulaDirector;


      /**
     * @var Departamento
     * @ORM\ManyToOne(targetEntity="Admin\UnadBundle\Entity\Departamento")
     * @ORM\JoinColumn(name="departamento_id", referencedColumnName="id",
     * nullable=true
     * )
     */
protected $departamento;


      /**
     * @var Zona
     * @ORM\ManyToOne(targetEntity="Admin\UnadBundle\Entity\Zona", inversedBy="centros")
     * @ORM\JoinColumn(name="zona_id", referencedColumnName="id",
     * nullable=false,
     * onDelete="CASCADE"
     * )
     */
protected $zona;



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
     * @return Centro
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
     * Set tipo
     *
     * @param string $tipo
     * @return Centro
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set director
     *
     * @param \AppBundle\Entity\User $director
     * @return Centro
     */
    public function setDirector(\AppBundle\Entity\User $director = null)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return \AppBundle\Entity\User
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Get Cedula Director
     *
     * @return integer
     */
    public function getCedulaDirector()
    {
        if ($this->director != null){
            return $this->director->getId();
        }else{
            return 0;
        }
    }

    /**
     * Set cedula Directo
     *
     * @param integer $cedulaDirector
     * @return Centro
     */
    public function setCedulaDirector($cedulaDirector)
    {
        $this->cedulaDirector = $cedulaDirector;

        return $this;
    }

    /**
     * Set zona
     *
     * @param \Admin\UnadBundle\Entity\Zona $zona
     * @return Centro
     */
    public function setZona(\Admin\UnadBundle\Entity\Zona $zona)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return \AppBundle\Entity\Zona
     */
    public function getZona()
    {
        return $this->zona;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->docentes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set departamento
     *
     * @param \Admin\UnadBundle\Entity\Departamento $departamento
     * @return Centro
     */
    public function setDepartamento(\Admin\UnadBundle\Entity\Departamento $departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return \Admin\UnadBundle\Entity\Departamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }
}
