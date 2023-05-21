<?php

namespace Admin\MedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="proyectos_inv")
 * @ORM\Entity(repositoryClass="Admin\MedBundle\Entity\ProyectoiRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Proyectoi {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $nombre;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $linea;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $estado;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",
     * nullable=false
     * )
     */
    protected $user;


     /**
     * @ORM\OneToMany(targetEntity="Admin\MedBundle\Entity\Productividad", mappedBy="proyecto")
     */
    protected $productos;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Proyectoi
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set linea
     *
     * @param string $linea
     * @return Proyectoi
     */
    public function setLinea($linea) {
        $this->linea = $linea;

        return $this;
    }

    /**
     * Get linea
     *
     * @return string
     */
    public function getLinea() {
        return $this->linea;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Proyectoi
     */
    public function setUser(\AppBundle\Entity\User $user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }


    /**
     * Set estado
     *
     * @param integer $estado
     * @return Proyectoi
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add productos
     *
     * @param \Admin\MedBundle\Entity\Productividad $productos
     * @return Proyectoi
     */
    public function addProducto(\Admin\MedBundle\Entity\Productividad $productos)
    {
        $this->productos[] = $productos;

        return $this;
    }

    /**
     * Remove productos
     *
     * @param \Admin\MedBundle\Entity\Productividad $productos
     */
    public function removeProducto(\Admin\MedBundle\Entity\Productividad $productos)
    {
        $this->productos->removeElement($productos);
    }

    /**
     * Get productos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductos()
    {
        return $this->productos;
    }
}
