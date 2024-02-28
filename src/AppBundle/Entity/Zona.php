<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="zona")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ZonaRepository")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="directorzona")
     * @ORM\JoinColumn(name="director_id", referencedColumnName="id",
     * nullable=false,
     * onDelete="CASCADE"
     * )
     */
protected $director;


/**
 * @var Nodo
 * @ORM\OneToOne(targetEntity="AppBundle\Entity\Centro")
 * @ORM\JoinColumn(name="nodo_id",referencedColumnName="id")
     */
 protected $nodo;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Centro", mappedBy="zona")
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
     * @param \AppBundle\Entity\User $director
     * @return Zona
     */
    public function setDirector(\AppBundle\Entity\User $director)
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
     * Constructor
     */
    public function __construct()
    {
        $this->centros = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add centros
     *
     * @param \AppBundle\Entity\Centro $centros
     * @return Zona
     */
    public function addCentro(\AppBundle\Entity\Centro $centros)
    {
        $this->centros[] = $centros;

        return $this;
    }

    /**
     * Remove centros
     *
     * @param \AppBundle\Entity\Centro $centros
     */
    public function removeCentro(\AppBundle\Entity\Centro $centros)
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
     * @param \AppBundle\Entity\Centro $nodo
     * @return Zona
     */
    public function setNodo(\AppBundle\Entity\Centro $nodo = null)
    {
        $this->nodo = $nodo;

        return $this;
    }

    /**
     * Get nodo
     *
     * @return \AppBundle\Entity\Centro
     */
    public function getNodo()
    {
        return $this->nodo;
    }
}
