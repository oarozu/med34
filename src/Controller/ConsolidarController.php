<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Base controller
 * @Route("/admin/panel")
 */
class ConsolidarController extends AbstractController
{


    /**
     * Mostrar Home
     * @Route("/", name="admin_consolidar_index", methods={"POST"})
     */
    public function indexAction()
    {
        return $this->render('Consolidar/index.html.twig');
    }

    /**
     * Mostrar Home
     * @Route("/auto", name="consolidar_index", methods={"POST"})
     */
    public function heterocursosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $q = "update evaluacion as a
        left join plangestion as p ON a.id = p.docente_id
        left join docente as d ON a.id = d.id
        set a.auto = p.autoevaluacion
        where d.periodo = 20171 and p.autoevaluacion is not null";
        $total = $connection->executeUpdate($q);

        return $this->render('Consolidar/result.html.twig', array(
            'result' => $total,
        ));
    }
}
