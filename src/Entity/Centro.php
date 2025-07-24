<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="centro")
 * @ORM\Entity(repositoryClass="App\Repository\CentroRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="directorcentro")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Departamento")
     * @ORM\JoinColumn(name="departamento_id", referencedColumnName="id",
     * nullable=true
     * )
     */
protected $departamento;


      /**
     * @var Zona
     * @ORM\ManyToOne(targetEntity="App\Entity\Zona", inversedBy="centros")
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
     * @param \App\Entity\User $director
     * @return Centro
     */
    public function setDirector(\App\Entity\User $director = null)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return \App\Entity\User
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
     * @param \App\Entity\Zona $zona
     * @return Centro
     */
    public function setZona(\App\Entity\Zona $zona)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return \App\Entity\Zona
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
     * @param \App\Entity\Departamento $departamento
     * @return Centro
     */
    public function setDepartamento(\App\Entity\Departamento $departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return \App\Entity\Departamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }
}
