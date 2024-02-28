<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="admin_user")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $emailp;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\Column(name="salt", type="string", length=255)
     */
    protected $salt;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(type="datetime")
     */
    private $updated;


    /**
     * se utilizó user_roles para no hacer conflicto al aplicar ->toArray en getRoles()
     * @ORM\ManyToMany(targetEntity="Roleu")
     * @ORM\JoinTable(name="user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $user_roles;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Escuela", mappedBy="decano")
     */

    protected $decano;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Escuela", mappedBy="secretaria")
     */

    protected $secretaria;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Zona", mappedBy="director")
     */
    protected $directorzona;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Centro", mappedBy="director")
     */
    protected $directorcentro;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Docente", mappedBy="user")
     */
    protected $docente;

    public function __construct()
    {
        $this->user_roles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Add user_roles
     *
     * @param \AppBundle\Entity\Roleu $userRoles
     */
    public function addRole(\AppBundle\Entity\Roleu $userRoles)
    {
        $this->user_roles[] = $userRoles;
    }

    public function setUserRoles($roles)
    {
        $this->user_roles = $roles;
    }

    /**
     * Get user_roles
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getUserRoles()
    {
        return $this->user_roles;
    }

    /**
     * Get roles
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->user_roles->toArray(); //IMPORTANTE: el mecanismo de seguridad de Sf2 requiere ésto como un array
    }

    /**
     * Compares this user to another to determine if they are the same.
     *
     * @param UserInterface $user The user
     * @return boolean True if equal, false othwerwise.
     */
    public function equals(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());

    }

    /**
     * Erases the user credentials.
     */
    public function eraseCredentials()
    {

    }

    /**
     * Serializes the content of the current User object
     * @return string
     */
    public function serialize()
    {
        return \json_encode(
            array($this->username, $this->password, $this->salt,
                $this->user_roles, $this->id));
    }

    /**
     * Unserializes the given string in the current User object
     * @param serialized
     */
    public function unserialize($serialized)
    {
        list($this->username, $this->password, $this->salt,
            $this->user_roles, $this->id) = \json_decode(
            $serialized);
    }


    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add user_roles
     *
     * @param \AppBundle\Entity\Roleu $userRoles
     * @return User
     */
    public function addUserRole(\AppBundle\Entity\Roleu $userRoles)
    {
        $this->user_roles[] = $userRoles;

        return $this;
    }

    /**
     * Remove user_roles
     *
     * @param \AppBundle\Entity\Roleu $userRoles
     */
    public function removeUserRole(\AppBundle\Entity\Roleu $userRoles)
    {
        $this->user_roles->removeElement($userRoles);
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return User
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }


    /**
     * Set id
     *
     * @param integer $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Add decano
     *
     * @param \AppBundle\Entity\Escuela $decano
     * @return User
     */
    public function addDecano(\AppBundle\Entity\Escuela $decano)
    {
        $this->decano[] = $decano;

        return $this;
    }

    /**
     * Remove decano
     *
     * @param \AppBundle\Entity\Escuela $decano
     */
    public function removeDecano(\AppBundle\Entity\Escuela $decano)
    {
        $this->decano->removeElement($decano);
    }

    /**
     * Get decano
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDecano()
    {
        return $this->decano;
    }

    /**
     * Add secretaria
     *
     * @param \AppBundle\Entity\Escuela $secretaria
     * @return User
     */
    public function addSecretarium(\AppBundle\Entity\Escuela $secretaria)
    {
        $this->secretaria[] = $secretaria;

        return $this;
    }

    /**
     * Remove secretaria
     *
     * @param \AppBundle\Entity\Escuela $secretaria
     */
    public function removeSecretarium(\AppBundle\Entity\Escuela $secretaria)
    {
        $this->secretaria->removeElement($secretaria);
    }

    /**
     * Get secretaria
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecretaria()
    {
        return $this->secretaria;
    }

    /**
     * Add directorzona
     *
     * @param \AppBundle\Entity\Zona $directorzona
     * @return User
     */
    public function addDirectorzona(\AppBundle\Entity\Zona $directorzona)
    {
        $this->directorzona[] = $directorzona;

        return $this;
    }

    /**
     * Remove directorzona
     *
     * @param \AppBundle\Entity\Zona $directorzona
     */
    public function removeDirectorzona(\AppBundle\Entity\Zona $directorzona)
    {
        $this->directorzona->removeElement($directorzona);
    }

    /**
     * Get directorzona
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirectorzona()
    {
        return $this->directorzona;
    }

    /**
     * Add directorcentro
     *
     * @param \AppBundle\Entity\Centro $directorcentro
     * @return User
     */
    public function addDirectorcentro(\AppBundle\Entity\Centro $directorcentro)
    {
        $this->directorcentro[] = $directorcentro;

        return $this;
    }

    /**
     * Remove directorcentro
     *
     * @param \AppBundle\Entity\Centro $directorcentro
     */
    public function removeDirectorcentro(\AppBundle\Entity\Centro $directorcentro)
    {
        $this->directorcentro->removeElement($directorcentro);
    }

    /**
     * Get directorcentro
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirectorcentro()
    {
        return $this->directorcentro;
    }

    /**
     * Add docente
     *
     * @param \AppBundle\Entity\Docente $docente
     * @return User
     */
    public function addDocente(\AppBundle\Entity\Docente $docente)
    {
        $this->docente[] = $docente;

        return $this;
    }

    /**
     * Remove docente
     *
     * @param \AppBundle\Entity\Docente $docente
     */
    public function removeDocente(\AppBundle\Entity\Docente $docente)
    {
        $this->docente->removeElement($docente);
    }

    /**
     * Get docente
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocente()
    {
        return $this->docente;
    }


    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }


    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdated(new \DateTime('now'));

        if ($this->getCreated() == null) {
            $this->setCreated(new \DateTime('now'));
        }
    }


    /**
     * Set emailp
     *
     * @param string $emailp
     * @return User
     */
    public function setEmailp($emailp)
    {
        $this->emailp = $emailp;

        return $this;
    }

    /**
     * Get emailp
     *
     * @return string
     */
    public function getEmailp()
    {
        return $this->emailp;
    }
}
