<?php
namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;


class Parabuscar{

    /**
     * @Assert\NotBlank()
     */
    public $texto;



        /**
     * @Assert\NotBlank()
     */
    public $parametro;


    /**
     * Set texto
     *
     * @param string $texto
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

        /**
     * Set parametro
     *
     * @param string $parametro
     */
    public function setParametro($parametro)
    {
        $this->parametro = $parametro;
    }

    /**
     * Get parametro
     *
     * @return string
     */
    public function getParametro()
    {
        return $this->parametro;
    }

}
