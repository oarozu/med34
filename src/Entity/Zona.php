<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="zona")
 * @ORM\Entity(repositoryClass="ZonaRepository")
 */

class Zona{

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
     * @var Director
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="directorzona")
     * @ORM\JoinColumn(name="director_id", referencedColumnName="id",
     * nullable=false,
     * onDelete="CASCADE"
     * )
     */
protected $director;


/**
 * @var Nodo
 * @ORM\OneToOne(targetEntity="App\Entity\Centro")
 * @ORM\JoinColumn(name="nodo_id",referencedColumnName="id")
     */
 protected $nodo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Centro", mappedBy="zona")
     */
    protected $centros;

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
     * @return Zona
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
     * Set director
     *
     * @param \App\Entity\User $director
     * @return Zona
     */
    public function setDirector(\App\Entity\User $director)
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
     * Constructor
     */
    public function __construct()
    {
        $this->centros = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add centros
     *
     * @param \App\Entity\Centro $centros
     * @return Zona
     */
    public function addCentro(\App\Entity\Centro $centros)
    {
        $this->centros[] = $centros;

        return $this;
    }

    /**
     * Remove centros
     *
     * @param \App\Entity\Centro $centros
     */
    public function removeCentro(\App\Entity\Centro $centros)
    {
        $this->centros->removeElement($centros);
    }

    /**
     * Get centros
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentros()
    {
        return $this->centros;
    }

    /**
     * Set nodo
     *
     * @param \App\Entity\Centro $nodo
     * @return Zona
     */
    public function setNodo(\App\Entity\Centro $nodo = null)
    {
        $this->nodo = $nodo;

        return $this;
    }

    /**
     * Get nodo
     *
     * @return \App\Entity\Centro
     */
    public function getNodo()
    {
        return $this->nodo;
    }
}
