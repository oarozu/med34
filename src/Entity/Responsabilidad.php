<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="actividad_resp")
 */
class Responsabilidad{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
     */
 protected $id;

  /**
     * @ORM\Column(type="string", length=255)
     */
protected $nombre;


 /**
 * @ORM\Column(type="integer", nullable=false)
 */
protected $codigo;

 /**
 * @ORM\Column(type="integer", nullable=false)
 */
protected $rol;

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
     * @return Responsabilidad
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
     * Set rol
     *
     * @param integer $rol
     * @return Responsabilidad
     */
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return integer
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set codigo
     *
     * @param integer $codigo
     * @return Responsabilidad
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return integer
     */
    public function getCodigo()
    {
        return $this->codigo;
    }
}
