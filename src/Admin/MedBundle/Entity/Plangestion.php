<?php

namespace Admin\MedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="plangestion")
 * @ORM\Entity(repositoryClass="Admin\MedBundle\Entity\PlangestionRepository")
 */
class Plangestion {

    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Docente
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Docente", inversedBy="plangestion")
     * @ORM\JoinColumn(name="docente_id",referencedColumnName="id")
     */
    protected $docente;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $fecha_creacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $fecha_cierre;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $fecha_autoevaluacion;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $dias;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    protected $observaciones;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $autoevaluacion;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $estado;

    /**
     * @ORM\OneToMany(targetEntity="Admin\MedBundle\Entity\Actividadplang", mappedBy="plang")
     */
    protected $actividades;

    /**
     * @ORM\OneToMany(targetEntity="Admin\MedBundle\Entity\Rolplang", mappedBy="plang")
     */
    protected $roles;

    /**
     * @ORM\OneToMany(targetEntity="Admin\MedBundle\Entity\Avalplang", mappedBy="plan")
     */
    protected $avales;


        /**
     * @ORM\OneToMany(targetEntity="Admin\MedBundle\Entity\Productividad", mappedBy="plang")
     */
    protected $productos;


    /**
    * @ORM\OneToOne(targetEntity="Admin\MedBundle\Entity\pdfPlang", cascade={"persist", "merge", "remove"})
    * @ORM\JoinColumn(name="pdf_id", referencedColumnName="id")
    */
    private $pdf;



    /**
     * Set fecha_creacion
     *
     * @param \DateTime $fechaCreacion
     * @return Plangestion
     */
    public function setFechaCreacion($fechaCreacion) {
        $this->fecha_creacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fecha_creacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion() {
        return $this->fecha_creacion;
    }

    /**
     * Set fecha_cierre
     *
     * @param \DateTime $fechaCierre
     * @return Plangestion
     */
    public function setFechaCierre($fechaCierre) {
        $this->fecha_cierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fecha_cierre
     *
     * @return \DateTime
     */
    public function getFechaCierre() {
        return $this->fecha_cierre;
    }

    /**
     * Set fecha_autoevaluacion
     *
     * @param \DateTime $fechaAutoevaluacion
     * @return Plangestion
     */
    public function setFechaAutoevaluacion($fechaAutoevaluacion) {
        $this->fecha_autoevaluacion = $fechaAutoevaluacion;

        return $this;
    }

    /**
     * Get fecha_autoevaluacion
     *
     * @return \DateTime
     */
    public function getFechaAutoevaluacion() {
        return $this->fecha_autoevaluacion;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Plangestion
     */
    public function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones() {
        return $this->observaciones;
    }

    /**
     * Set autoevaluacion
     *
     * @param float $autoevaluacion
     * @return Plangestion
     */
    public function setAutoevaluacion($autoevaluacion) {
        $this->autoevaluacion = $autoevaluacion;

        return $this;
    }

    /**
     * Get autoevaluacion
     *
     * @return float
     */
    public function getAutoevaluacion() {
        return $this->autoevaluacion;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return Plangestion
     */
    public function setEstado($estado) {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer
     */
    public function getEstado() {
        if ($this->estado == 0)
            return 'Sin Cerrar';
        elseif ($this->estado == 1)
            return 'Cerrado';
        elseif ($this->estado == 5)
            return 'Confirmado';
        elseif ($this->estado == 10)
            return 'Autoevaluado';
        else
            return $this->estado;
    }

    /**
     * Set id
     *
     * @param \AppBundle\Entity\Docente $id
     * @return Plangestion
     */
    public function setId(\AppBundle\Entity\Docente $id) {
        $this->id = $id;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->actividades = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add actividades
     *
     * @param \Admin\MedBundle\Entity\Actividadplang $actividades
     * @return Plangestion
     */
    public function addActividade(\Admin\MedBundle\Entity\Actividadplang $actividades) {
        $this->actividades[] = $actividades;

        return $this;
    }

    /**
     * Remove actividades
     *
     * @param \Admin\MedBundle\Entity\Actividadplang $actividades
     */
    public function removeActividade(\Admin\MedBundle\Entity\Actividadplang $actividades) {
        $this->actividades->removeElement($actividades);
    }

    /**
     * Get actividades
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActividades() {
        return $this->actividades;
    }

    /**
     * Add avales
     *
     * @param \Admin\MedBundle\Entity\Avalplang $avales
     * @return Plangestion
     */
    public function addAvale(\Admin\MedBundle\Entity\Avalplang $avales) {
        $this->avales[] = $avales;

        return $this;
    }

    /**
     * Remove avales
     *
     * @param \Admin\MedBundle\Entity\Avalplang $avales
     */
    public function removeAvale(\Admin\MedBundle\Entity\Avalplang $avales) {
        $this->avales->removeElement($avales);
    }

    /**
     * Get avales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAvales() {
        return $this->avales;
    }

    /**
     * Add roles
     *
     * @param \Admin\MedBundle\Entity\Rolplang $roles
     * @return Plangestion
     */
    public function addRole(\Admin\MedBundle\Entity\Rolplang $roles) {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Admin\MedBundle\Entity\Rolplang $roles
     */
    public function removeRole(\Admin\MedBundle\Entity\Rolplang $roles) {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles() {
        return $this->roles;
    }



    /**
     * Set docente
     *
     * @param \AppBundle\Entity\Docente $docente
     * @return Plangestion
     */
    public function setDocente(\AppBundle\Entity\Docente $docente) {
        $this->docente = $docente;

        return $this;
    }

    /**
     * Get docente
     *
     * @return \AppBundle\Entity\Docente
     */
    public function getDocente() {
        return $this->docente;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set pdf
     *
     * @param \Admin\MedBundle\Entity\pdfPlang $pdf
     * @return Plangestion
     */
    public function setPdf(\Admin\MedBundle\Entity\pdfPlang $pdf = null) {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * Get pdf
     *
     * @return \Admin\MedBundle\Entity\pdfPlang
     */
    public function getPdf() {
        return $this->pdf;
    }


    /**
     * Set dias
     *
     * @param integer $dias
     * @return Plangestion
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Add productos
     *
     * @param \Admin\MedBundle\Entity\Productividad $productos
     * @return Plangestion
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
