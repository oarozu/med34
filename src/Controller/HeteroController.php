<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Entity\Heteroeval;
use App\Entity\Heterocursos;

/**
 * Plangestion controller.
 *
 * @Route("/unad/hetero")
 */
class HeteroController extends AbstractController {

    /**
     * Lists all hetero semestre actual
     *
     * @Route("/pcurso/{pe}", name="hetero_index", methods={"GET"})
     * @Template("Hetero/index.html.twig")
     */
    public function indexAction($pe) {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('App:Heterocursos')->findBy(array('semestre' => $pe));
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Mostrar promedio escuelas
     * @Route("/prom_esc", name="hetero_prom_esc", methods={"GET"})
     * @Template("Hetero/promescuela.html.twig")
     */
    public function heteroescuelasAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $datas = $em->getRepository('App:Heteroeval')->getPromedioescuela();


       $session = $request->getSession();

       $miescuela = $session->get('escuelaid');


        return array(
            'data' => $datas,
            'miescuela' => $miescuela
        );
    }

    /**
     * Listado de hetero escuela en periodo x
     * @Route("/es_pe/{esc}/{pe}", name="hetero_esc_per", methods={"GET"})
     * @Template("Hetero/heteroescuelas.html.twig")
     */
    public function escuelaperiodoAction($esc, $pe) {


        $em = $this->getDoctrine()->getManager();
        $docentes = $em->getRepository('App:Docente')->findBy(array('periodo' => $pe, 'escuela' => $esc));
        $hetero = $em->getRepository('App:Heteroeval')->findBy(array('docente' => $docentes));
        $escuela = $em->getRepository('App:Escuela')->find($esc);
        return array(
            'hetero' => $hetero,
            'escuela' => $escuela,
            'pe' => $pe
        );
    }

}