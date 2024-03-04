<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="red_dofe")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RedDofeRepository")
 */
class RedDofe {

    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var Docente
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Docente")
     * @ORM\JoinColumn(name="docente_id", referencedColumnName="id", nullable=false)
     */
    protected $docente;

    /**
     * @var Rol
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Rolacademico")
     * @ORM\JoinColumn(name="rol_id", referencedColumnName="id", nullable=false)
     */
    protected $rol;

    /**
     * @var Escuela
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Escuela")
     * @ORM\JoinColumn(name="escuela_id", referencedColumnName="id", nullable=false)
     */
    protected $escuela;

    /**
     * @var Zona
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Zona")
     * @ORM\JoinColumn(name="zona_id", referencedColumnName="id", nullable=false)
     */
    protected $zona;

    /**
     * @var Programa
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Programa")
     * @ORM\JoinColumn(name="programa_id", referencedColumnName="id", nullable=true)
     */
    protected $programa;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $periodo;

    /**
     * @var Evaluador
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="evaluador_id", referencedColumnName="id",
     * nullable=false
     * )
     */
    protected $evaluador;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $perfil;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $calificacion;


    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    protected $fecha;

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
     * Set periodo
     *
     * @param integer $periodo
     * @return RedDofe
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
     * Set perfil
     *
     * @param string $perfil
     * @return RedDofe
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get perfil
     *
     * @return string
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set calificacion
     *
     * @param string $calificacion
     * @return RedDofe
     */
    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;

        return $this;
    }

    /**
     * Get calificacion
     *
     * @return string
     */
    public function getCalificacion()
    {
        return $this->calificacion;
    }

    /**
     * Set docente
     *
     * @param \AppBundle\Entity\Docente $docente
     * @return RedDofe
     */
    public function setDocente(\AppBundle\Entity\Docente $docente)
    {
        $this->docente = $docente;

        return $this;
    }

    /**
     * Get docente
     *
     * @return \AppBundle\Entity\Docente
     */
    public function getDocente()
    {
        return $this->docente;
    }

    /**
     * Set rol
     *
     * @param \AppBundle\Entity\Rolacademico $rol
     * @return RedDofe
     */
    public function setRol(\AppBundle\Entity\Rolacademico $rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \AppBundle\Entity\Rolacademico
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set escuela
     *
     * @param \AppBundle\Entity\Escuela $escuela
     * @return RedDofe
     */
    public function setEscuela(\AppBundle\Entity\Escuela $escuela)
    {
        $this->escuela = $escuela;

        return $this;
    }

    /**
     * Get escuela
     *
     * @return \AppBundle\Entity\Escuela
     */
    public function getEscuela()
    {
        return $this->escuela;
    }

    /**
     * Set zona
     *
     * @param \AppBundle\Entity\Zona $zona
     * @return RedDofe
     */
    public function setZona(\AppBundle\Entity\Zona $zona)
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
     * Set programa
     *
     * @param \AppBundle\Entity\Programa $programa
     * @return RedDofe
     */
    public function setPrograma(\AppBundle\Entity\Programa $programa = null)
    {
        $this->programa = $programa;

        return $this;
    }

    /**
     * Get programa
     *
     * @return \AppBundle\Entity\Programa
     */
    public function getPrograma()
    {
        return $this->programa;
    }

    /**
     * Set evaluador
     *
     * @param \AppBundle\Entity\User $evaluador
     * @return RedDofe
     */
    public function setEvaluador(\AppBundle\Entity\User $evaluador)
    {
        $this->evaluador = $evaluador;

        return $this;
    }

    /**
     * Get evaluador
     *
     * @return \AppBundle\Entity\User
     */
    public function getEvaluador()
    {
        return $this->evaluador;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return RedDofe
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}
