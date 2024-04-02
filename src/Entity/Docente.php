<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="docente")
 * @ORM\Entity(repositoryClass="DocenteRepository")
 */
class Docente{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="NONE")
     */
 protected $id;

 /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     */
protected $modalidad;

 /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
protected $vinculacion;

 /**
     * @ORM\Column(type="string", length=50)
     */
protected $cargo;

 /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
protected $resolucion;

 /**
     * @ORM\Column(type="string", length=15)
     */
protected $perfil;

    /**
    * @ORM\Column(type="smallint", nullable=true)
    */
protected $periodo;

 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
protected $fecha_inicio;

 /**
  * @ORM\Column(type="datetime", nullable=true)
  */
protected $fecha_fin;

/**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="docente")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",
     * nullable=false
     * )
     */
protected $user;


/**
     * @var Escuela
     * @ORM\ManyToOne(targetEntity="App\Entity\Escuela", inversedBy="docentes")
     * @ORM\JoinColumn(name="escuela_id", referencedColumnName="id",
     * nullable=false
     * )
     */
protected $escuela;

/**
     * @var Programa
     * @ORM\ManyToOne(targetEntity="App\Entity\Programa", inversedBy="docentes")
     * @ORM\JoinColumn(name="programa_id", referencedColumnName="id",
     * nullable=true
     * )
     */
protected $programa;


/**
     * @var Centro
     * @ORM\ManyToOne(targetEntity="App\Entity\Centro")
     * @ORM\JoinColumn(name="centro_id", referencedColumnName="id",
     * nullable=false
     * )
     */
protected $centro;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Planmejoramiento", mappedBy="docente")
     */
    protected $planmejoramiento;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProgramaPeriodo", mappedBy="lider")
     */
    protected $lider;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Oferta", mappedBy="director")
     */
    protected $director;


   /**
    * @ORM\OneToOne(targetEntity="App\Entity\Plangestion", mappedBy="docente")
    */
   protected $plangestion;


    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Tutor", mappedBy="docente")
    */
    protected $tutoria;


    /**
    * @ORM\OneToMany(targetEntity="App\Entity\coevalPares", mappedBy="evaluado")
    */
   protected $coevaldepar;


    /**
    * @ORM\OneToOne(targetEntity="App\Entity\Heteroeval", mappedBy="docente")
    */
   protected $heteroeval;

    /**
    * @ORM\OneToOne(targetEntity="App\Entity\Evaluacion", mappedBy="docente")
    */
   protected $evaluacion;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Terna", mappedBy="docente")
    */
   protected $ternado;

    /**
     * Set id
     *
     * @param integer $id
     * @return Docente
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
     * Set modalidad
     *
     * @param string $modalidad
     * @return Docente
     */
    public function setModalidad($modalidad)
    {
        $this->modalidad = $modalidad;

        return $this;
    }

    /**
     * Get modalidad
     *
     * @return string
     */
    public function getModalidad()
    {
            if ($this->modalidad == 'TC'){
            return 'Tiempo Completo';
        }
        Elseif($this->modalidad == 'MT'){
            return 'Medio Tiempo';
        }
        Elseif($this->modalidad == 'HC'){
            return 'Hora Catedra';
        }
        Else {
          return $this->modalidad;
        }
    }

    /**
     * Set vinculacion
     *
     * @param string $vinculacion
     * @return Docente
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
        if ($this->vinculacion == 'DC'){
            return 'De Carrera';
        }
        Elseif($this->vinculacion == 'DO'){
            return 'Ocasional';
        }
        Elseif($this->vinculacion == 'HC'){
            return 'Hora Catedra';
        }
        Else {
          return $this->vinculacion;
        }
    }

    /**
     * Set cargo
     *
     * @param string $cargo
     * @return Docente
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set resolucion
     *
     * @param string $resolucion
     * @return Docente
     */
    public function setResolucion($resolucion)
    {
        $this->resolucion = $resolucion;

        return $this;
    }

    /**
     * Get resolucion
     *
     * @return string
     */
    public function getResolucion()
    {
        return $this->resolucion;
    }

    /**
     * Set perfil
     *
     * @param string $perfil
     * @return Docente
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
     * Set user
     *
     * @param \App\Entity\User $user
     * @return Docente
     */
    public function setUser(\App\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set escuela
     *
     * @param \App\Entity\Escuela $escuela
     * @return Docente
     */
    public function setEscuela(\App\Entity\Escuela $escuela)
    {
        $this->escuela = $escuela;

        return $this;
    }

    /**
     * Get escuela
     *
     * @return \App\Entity\Escuela
     */
    public function getEscuela()
    {
        return $this->escuela;
    }

    /**
     * Set centro
     *
     * @param \App\Entity\Centro $centro
     * @return Docente
     */
    public function setCentro(\App\Entity\Centro $centro)
    {
        $this->centro = $centro;

        return $this;
    }

    /**
     * Get centro
     *
     * @return \App\Entity\Centro
     */
    public function getCentro()
    {
        return $this->centro;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->planmejoramiento = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set programa
     *
     * @param \App\Entity\Programa $programa
     * @return Docente
     */
    public function setPrograma(\App\Entity\Programa $programa = null)
    {
        $this->programa = $programa;

        return $this;
    }

    /**
     * Get programa
     *
     * @return \App\Entity\Programa
     */
    public function getPrograma()
    {
        return $this->programa;
    }

    /**
     * Add planmejoramiento
     *
     * @param \App\Entity\Planmejoramiento $planmejoramiento
     * @return Docente
     */
    public function addPlanmejoramiento(\App\Entity\Planmejoramiento $planmejoramiento)
    {
        $this->planmejoramiento[] = $planmejoramiento;

        return $this;
    }

    /**
     * Remove planmejoramiento
     *
     * @param \App\Entity\Planmejoramiento $planmejoramiento
     */
    public function removePlanmejoramiento(\App\Entity\Planmejoramiento $planmejoramiento)
    {
        $this->planmejoramiento->removeElement($planmejoramiento);
    }

    /**
     * Get planmejoramiento
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanmejoramiento()
    {
        return $this->planmejoramiento;
    }


    /**
     * Add director
     *
     * @param \App\Entity\Oferta $director
     * @return Docente
     */
    public function addDirector(\App\Entity\Oferta $director)
    {
        $this->director[] = $director;

        return $this;
    }

    /**
     * Remove director
     *
     * @param \App\Entity\Oferta $director
     */
    public function removeDirector(\App\Entity\Oferta $director)
    {
        $this->director->removeElement($director);
    }

    /**
     * Get director
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Add tutoria
     *
     * @param \App\Entity\Curso $tutoria
     * @return Docente
     */
    public function addTutorium(\App\Entity\Curso $tutoria)
    {
        $this->tutoria[] = $tutoria;

        return $this;
    }


    /**
     * Set plangestion
     *
     * @param \App\Entity\Plangestion $plangestion
     * @return Docente
     */
    public function setPlangestion(\App\Entity\Plangestion $plangestion = null)
    {
        $this->plangestion = $plangestion;

        return $this;
    }

    /**
     * Get plangestion
     *
     * @return \App\Entity\Plangestion
     */
    public function getPlangestion()
    {
        return $this->plangestion;
    }


    /**
     * Add coevaldepar
     *
     * @param \App\Entity\coevalPares $coevaldepar
     * @return Docente
     */
    public function addCoevaldepar(\App\Entity\coevalPares $coevaldepar)
    {
        $this->coevaldepar[] = $coevaldepar;

        return $this;
    }

    /**
     * Remove coevaldepar
     *
     * @param \App\Entity\coevalPares $coevaldepar
     */
    public function removeCoevaldepar(\App\Entity\coevalPares $coevaldepar)
    {
        $this->coevaldepar->removeElement($coevaldepar);
    }

    /**
     * Get coevaldepar
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoevaldepar()
    {
        return $this->coevaldepar;
    }


    /**
     * Set heteroeval
     *
     * @param \App\Entity\Heteroeval $heteroeval
     * @return Docente
     */
    public function setHeteroeval(\App\Entity\Heteroeval $heteroeval = null)
    {
        $this->heteroeval = $heteroeval;

        return $this;
    }

    /**
     * Get heteroeval
     *
     * @return \App\Entity\Heteroeval
     */
    public function getHeteroeval()
    {
        return $this->heteroeval;
    }

    /**
     * Set evaluacion
     *
     * @param \App\Entity\Evaluacion $evaluacion
     * @return Docente
     */
    public function setEvaluacion(\App\Entity\Evaluacion $evaluacion = null)
    {
        $this->evaluacion = $evaluacion;

        return $this;
    }

    /**
     * Get evaluacion
     *
     * @return \App\Entity\Evaluacion
     */
    public function getEvaluacion()
    {
        return $this->evaluacion;
    }

    /**
     * Set periodo
     *
     * @param integer $periodo
     * @return Docente
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
     * Remove tutoria
     *
     * @param \App\Entity\Tutor $tutoria
     */
    public function removeTutorium(\App\Entity\Tutor $tutoria)
    {
        $this->tutoria->removeElement($tutoria);
    }

    /**
     * Get tutoria
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTutoria()
    {
        return $this->tutoria;
    }

    /**
     * Add ternado
     *
     * @param \App\Entity\Terna $ternado
     * @return Docente
     */
    public function addTernado(\App\Entity\Terna $ternado)
    {
        $this->ternado[] = $ternado;

        return $this;
    }

    /**
     * Remove ternado
     *
     * @param \App\Entity\Terna $ternado
     */
    public function removeTernado(\App\Entity\Terna $ternado)
    {
        $this->ternado->removeElement($ternado);
    }

    /**
     * Get ternado
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTernado()
    {
        return $this->ternado;
    }

    /**
     * Set fecha_inicio
     *
     * @param \DateTime $fechaInicio
     * @return Docente
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fecha_inicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fecha_inicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fecha_inicio;
    }

    /**
     * Set fecha_fin
     *
     * @param \DateTime $fechaFin
     * @return Docente
     */
    public function setFechaFin($fechaFin)
    {
        $this->fecha_fin = $fechaFin;

        return $this;
    }

    /**
     * Get fecha_fin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    /**
     * Add lider
     *
     * @param \App\Entity\ProgramaPeriodo $lider
     * @return Docente
     */
    public function addLider(\App\Entity\ProgramaPeriodo $lider)
    {
        $this->lider[] = $lider;

        return $this;
    }

    /**
     * Remove lider
     *
     * @param \App\Entity\ProgramaPeriodo $lider
     */
    public function removeLider(\App\Entity\ProgramaPeriodo $lider)
    {
        $this->lider->removeElement($lider);
    }

    /**
     * Get lider
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLider()
    {
        return $this->lider;
    }
}
