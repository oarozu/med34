<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="rol_plang", uniqueConstraints={@ORM\UniqueConstraint(columns={"plan_id", "rol_id"})})
 * @ORM\Entity(repositoryClass="rolplanglRepository")
 */
class Rolplang{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
 */
 protected $id;

/**
 * @ORM\Column(type="integer", nullable=true)
 */
protected $horas;


/**
 * @ORM\Column(type="decimal", scale=1, nullable=true)
 */
protected $semanas;


/**
* @ORM\Column(type="string", length=2500, nullable=true)
*/
protected $descripcion;

/**
* @var Plang
* @ORM\ManyToOne(targetEntity="App\Entity\Plangestion", inversedBy="roles")
* @ORM\JoinColumn(name="plan_id", referencedColumnName="id", nullable=false)
*/
protected $plang;

/**
* @var Rol
* @ORM\ManyToOne(targetEntity="App\Entity\Rolacademico")
* @ORM\JoinColumn(name="rol_id", referencedColumnName="id", nullable=false)
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
     * Set horas
     *
     * @param string $horas
     * @return Rolplang
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return string
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set plang
     *
     * @param \App\Entity\Plangestion $plang
     * @return Rolplang
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
     * Set rol
     *
     * @param \App\Entity\Rolacademico $rol
     * @return Rolplang
     */
    public function setRol(\App\Entity\Rolacademico $rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \App\Entity\Rolacademico
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Rolplang
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
     * Set semanas
     *
     * @param string $semanas
     * @return Rolplang
     */
    public function setSemanas($semanas)
    {
        $this->semanas = $semanas;

        return $this;
    }

    /**
     * Get semanas
     *
     * @return string
     */
    public function getSemanas()
    {
        return $this->semanas;
    }
}
