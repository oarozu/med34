<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="plangestion")
 * @ORM\Entity(repositoryClass="PlangestionRepository")
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
     * @ORM\OneToOne(targetEntity="App\Entity\Docente", inversedBy="plangestion")
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
     * @ORM\OneToMany(targetEntity="App\Entity\Actividadplang", mappedBy="plang")
     */
    protected $actividades;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rolplang", mappedBy="plang")
     */
    protected $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Avalplang", mappedBy="plan")
     */
    protected $avales;


        /**
     * @ORM\OneToMany(targetEntity="App\Entity\Productividad", mappedBy="plang")
     */
    protected $productos;


    /**
    * @ORM\OneToOne(targetEntity="App\Entity\pdfPlang", cascade={"persist", "merge", "remove"})
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
     * @param \App\Entity\Docente $id
     * @return Plangestion
     */
    public function setId(\App\Entity\Docente $id) {
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
     * @param \App\Entity\Actividadplang $actividades
     * @return Plangestion
     */
    public function addActividade(\App\Entity\Actividadplang $actividades) {
        $this->actividades[] = $actividades;

        return $this;
    }

    /**
     * Remove actividades
     *
     * @param \App\Entity\Actividadplang $actividades
     */
    public function removeActividade(\App\Entity\Actividadplang $actividades) {
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
     * @param \App\Entity\Avalplang $avales
     * @return Plangestion
     */
    public function addAvale(\App\Entity\Avalplang $avales) {
        $this->avales[] = $avales;

        return $this;
    }

    /**
     * Remove avales
     *
     * @param \App\Entity\Avalplang $avales
     */
    public function removeAvale(\App\Entity\Avalplang $avales) {
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
     * @param \App\Entity\Rolplang $roles
     * @return Plangestion
     */
    public function addRole(\App\Entity\Rolplang $roles) {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \App\Entity\Rolplang $roles
     */
    public function removeRole(\App\Entity\Rolplang $roles) {
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
     * @param \App\Entity\Docente $docente
     * @return Plangestion
     */
    public function setDocente(\App\Entity\Docente $docente) {
        $this->docente = $docente;

        return $this;
    }

    /**
     * Get docente
     *
     * @return \App\Entity\Docente
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
     * @param \App\Entity\pdfPlang $pdf
     * @return Plangestion
     */
    public function setPdf(\App\Entity\pdfPlang $pdf) {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * Get pdf
     *
     * @return \App\Entity\pdfPlang
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
     * @param \App\Entity\Productividad $productos
     * @return Plangestion
     */
    public function addProducto(\App\Entity\Productividad $productos)
    {
        $this->productos[] = $productos;

        return $this;
    }

    /**
     * Remove productos
     *
     * @param \App\Entity\Productividad $productos
     */
    public function removeProducto(\App\Entity\Productividad $productos)
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
