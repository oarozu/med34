<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="admin_user")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\OneToMany(targetEntity="App\Entity\Escuela", mappedBy="decano")
     */

    protected $decano;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Escuela", mappedBy="secretaria")
     */

    protected $secretaria;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Zona", mappedBy="director")
     */
    protected $directorzona;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Centro", mappedBy="director")
     */
    protected $directorcentro;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Docente", mappedBy="user")
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
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
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
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Add user_roles
     *
     * @param \App\Entity\Roleu $userRoles
     */
    public function addRole(\App\Entity\Roleu $userRoles)
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
      //  return $this->user_roles->toArray(); //IMPORTANTE: el mecanismo de seguridad de Sf2 requiere ésto como un array
        return $this->extractRoles($this->user_roles->toArray());
    }

    public function extractRoles($roles){
        foreach ($roles as $role){
            $rolesName[] = $role->getName();
        }
        return $rolesName;
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
     * @param \App\Entity\Roleu $userRoles
     * @return User
     */
    public function addUserRole(\App\Entity\Roleu $userRoles)
    {
        $this->user_roles[] = $userRoles;

        return $this;
    }

    /**
     * Remove user_roles
     *
     * @param \App\Entity\Roleu $userRoles
     */
    public function removeUserRole(\App\Entity\Roleu $userRoles)
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
     * @param \App\Entity\Escuela $decano
     * @return User
     */
    public function addDecano(\App\Entity\Escuela $decano)
    {
        $this->decano[] = $decano;

        return $this;
    }

    /**
     * Remove decano
     *
     * @param \App\Entity\Escuela $decano
     */
    public function removeDecano(\App\Entity\Escuela $decano)
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
     * @param \App\Entity\Escuela $secretaria
     * @return User
     */
    public function addSecretarium(\App\Entity\Escuela $secretaria)
    {
        $this->secretaria[] = $secretaria;

        return $this;
    }

    /**
     * Remove secretaria
     *
     * @param \App\Entity\Escuela $secretaria
     */
    public function removeSecretarium(\App\Entity\Escuela $secretaria)
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
     * @param \App\Entity\Zona $directorzona
     * @return User
     */
    public function addDirectorzona(\App\Entity\Zona $directorzona)
    {
        $this->directorzona[] = $directorzona;

        return $this;
    }

    /**
     * Remove directorzona
     *
     * @param \App\Entity\Zona $directorzona
     */
    public function removeDirectorzona(\App\Entity\Zona $directorzona)
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
     * @param \App\Entity\Centro $directorcentro
     * @return User
     */
    public function addDirectorcentro(\App\Entity\Centro $directorcentro)
    {
        $this->directorcentro[] = $directorcentro;

        return $this;
    }

    /**
     * Remove directorcentro
     *
     * @param \App\Entity\Centro $directorcentro
     */
    public function removeDirectorcentro(\App\Entity\Centro $directorcentro)
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
     * @param \App\Entity\Docente $docente
     * @return User
     */
    public function addDocente(\App\Entity\Docente $docente)
    {
        $this->docente[] = $docente;

        return $this;
    }

    /**
     * Remove docente
     *
     * @param \App\Entity\Docente $docente
     */
    public function removeDocente(\App\Entity\Docente $docente)
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
