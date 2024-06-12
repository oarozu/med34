<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Newpass {

    /**
     * @Assert\NotBlank()
     */
    public $username;

    /**
     * @Assert\NotBlank()
     */
    public $email;

    /**
     * @Assert\NotBlank()
     */
    public $unidad;

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @param string $unidad
     */
    public function setUnidad($unidad) {
        $this->unidad = $unidad;
    }

}
