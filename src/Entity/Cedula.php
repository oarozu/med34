<?php
namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


class Cedula{


    /**
     * @Assert\Regex("/[0-9]{6,10}/", message="Incluya el formato aceptado, solo digitos hasta 10")
     */
    public $cedula;


    public function getCedula()
    {
        return $this->cedula;
    }
}
