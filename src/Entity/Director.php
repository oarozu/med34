<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="director", uniqueConstraints={@ORM\UniqueConstraint(columns={"centro_id", "periodo_id"})})
 */
class Director{

/**
 * @ORM\Id
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\GeneratedValue(strategy="AUTO")
 */
 protected $id;

/**
* @var Centro
* @ORM\ManyToOne(targetEntity="App\Entity\Centro")
* @ORM\JoinColumn(name="centro_id", referencedColumnName="id", nullable=false)
*/
protected $centro;

/**
* @var Periodo
* @ORM\ManyToOne(targetEntity="App\Entity\Periodoe")
* @ORM\JoinColumn(name="periodo_id", referencedColumnName="id", nullable=false)
*/
protected $periodo;

/**
* @var Director
* @ORM\ManyToOne(targetEntity="App\Entity\User")
* @ORM\JoinColumn(name="director_id", referencedColumnName="id",
* nullable=true
* )
*/
protected $director;

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
     * Set centro
     *
     * @param \App\Entity\Centro $centro
     * @return Director
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
     * Set periodo
     *
     * @param \App\Entity\Periodoe $periodo
     * @return Director
     */
    public function setPeriodo(\App\Entity\Periodoe $periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return \App\Entity\Periodoe
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }


    /**
     * Set director
     *
     * @param \App\Entity\User $director
     * @return Director
     */
    public function setDirector(\App\Entity\User $director = null)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return \App\Entity\User
     */
    public function getDirector()
    {
        return $this->director;
    }
}
