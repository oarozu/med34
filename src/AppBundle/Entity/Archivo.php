<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="archivo")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ArchivoRepository")
 */
class Archivo{


 /**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="NONE")
 */
 protected $id;

    /**
    * @ORM\Column(type="string", length=255)
    */
    protected $cedula;

    /**
    * @ORM\Column(type="string", length=255)
    */
   private $nombres;

    /**
    * @ORM\Column(type="string", length=255)
    */
   private $apellidos;

    /**
    * @ORM\Column(type="string", length=10)
    */
    protected $vinculacion;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     */
    protected $sigla;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $centro;

    /**
    * @ORM\Column(type="smallint")
    */
    protected $zona;


    /**
    * @ORM\Column(type="smallint")
    */
    protected $periodo;

    /**
    * @ORM\Column(type="decimal", scale=2, nullable=true)
    */
    protected $final;

    /**
     * Set id
     *
     * @param integer $id
     * @return Archivo
     */
    public function setId($id)
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

    /**
     * Set cedula
     *
     * @param string $cedula
     * @return Archivo
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return string
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     * @return Archivo
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Archivo
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set vinculacion
     *
     * @param string $vinculacion
     * @return Archivo
     */
    public function setVinculacion($vinculacion)
    {
        $this->vinculacion = $vinculacion;

        return $this;
    }

    /**
     * Get vinculacion
     *
     * @return string
     */
    public function getVinculacion()
    {
        return $this->vinculacion;
    }

    /**
     * Set sigla
     *
     * @param string $sigla
     * @return Archivo
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * Get sigla
     *
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set centro
     *
     * @param string $centro
     * @return Archivo
     */
    public function setCentro($centro)
    {
        $this->centro = $centro;

        return $this;
    }

    /**
     * Get centro
     *
     * @return string
     */
    public function getCentro()
    {
        return $this->centro;
    }

    /**
     * Set zona
     *
     * @param integer $zona
     * @return Archivo
     */
    public function setZona($zona)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return integer
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set periodo
     *
     * @param integer $periodo
     * @return Archivo
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return integer
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set final
     *
     * @param string $final
     * @return Archivo
     */
    public function setFinal($final)
    {
        $this->final = $final;

        return $this;
    }

    /**
     * Get final
     *
     * @return string
     */
    public function getFinal()
    {
        return $this->final;
    }
}
