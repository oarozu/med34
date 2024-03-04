<?php
namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;


class OfertaDatos{

    /**
     * @Assert\Regex("/[0-9]{6,10}/", message="Incluya el formato aceptado, solo digitos hasta 10")
     */
    public $cedula;

    /**
     * @Assert\NotBlank()
     */
    public $periodo;
}

