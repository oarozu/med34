<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="escuela")
 * @ORM\Entity(repositoryClass="EscuelaRepository")
 */

class Escuela{

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
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     */

protected $sigla;


      /**
     * @var Decano
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="decano")
     * @ORM\JoinColumn(name="decano_id", referencedColumnName="id",
     * nullable=false
     * )
     */
protected $decano;


      /**
     * @var Secretaria
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="secretaria")
     * @ORM\JoinColumn(name="secretaria_id", referencedColumnName="id",
     * nullable=true
     * )
     */
protected $secretaria;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Programa", mappedBy="escuela")
     */
    protected $programas;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Docente", mappedBy="escuela")
     */
    protected $docentes;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Terna", mappedBy="escuela")
    */
    protected $ternados;

    /**
     * Set id
     * @param integer $id
     * @return integer
     */
    public function setId($id)
    {
        return $this->id = $id;
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
     * Set nombre
     *
     * @param string $nombre
     * @return Escuela
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
     * Set sigla
     *
     * @param string $sigla
     * @return Escuela
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
     * Set decano
     *
     * @param \App\Entity\User $decano
     * @return Escuela
     */
    public function setDecano(\App\Entity\User $decano)
    {
        $this->decano = $decano;

        return $this;
    }

    /**
     * Get decano
     *
     * @return \App\Entity\User
     */
    public function getDecano()
    {
        return $this->decano;
    }

    /**
     * Set secretaria
     *
     * @param \App\Entity\User $secretaria
     * @return Escuela
     */
    public function setSecretaria(\App\Entity\User $secretaria = null)
    {
        $this->secretaria = $secretaria;

        return $this;
    }

    /**
     * Get secretaria
     *
     * @return \App\Entity\User
     */
    public function getSecretaria()
    {
        return $this->secretaria;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->docentes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add programas
     *
     * @param \App\Entity\Programa $programas
     * @return Escuela
     */
    public function addPrograma(\App\Entity\Programa $programas)
    {
        $this->programas[] = $programas;

        return $this;
    }

    /**
     * Remove programas
     *
     * @param \App\Entity\Programa $programas
     */
    public function removePrograma(\App\Entity\Programa $programas)
    {
        $this->programas->removeElement($programas);
    }

    /**
     * Get programas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramas()
    {
        return $this->programas;
    }

    /**
     * Add docentes
     *
     * @param \App\Entity\Docente $docentes
     * @return Escuela
     */
    public function addDocente(\App\Entity\Docente $docentes)
    {
        $this->docentes[] = $docentes;

        return $this;
    }

    /**
     * Remove docentes
     *
     * @param \App\Entity\Docente $docentes
     */
    public function removeDocente(\App\Entity\Docente $docentes)
    {
        $this->docentes->removeElement($docentes);
    }

    /**
     * Get docentes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocentes()
    {
        return $this->docentes;
    }

    /**
     * Add ternados
     *
     * @param \App\Entity\Terna $ternados
     * @return Escuela
     */
    public function addTernado(\App\Entity\Terna $ternados)
    {
        $this->ternados[] = $ternados;

        return $this;
    }

    /**
     * Remove ternados
     *
     * @param \App\Entity\Terna $ternados
     */
    public function removeTernado(\App\Entity\Terna $ternados)
    {
        $this->ternados->removeElement($ternados);
    }

    /**
     * Get ternados
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTernados()
    {
        return $this->ternados;
    }
}
