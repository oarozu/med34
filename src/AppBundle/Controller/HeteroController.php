<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Heteroeval;
use AppBundle\Entity\Heterocursos;

/**
 * Plangestion controller.
 *
 * @Route("/unad/hetero")
 */
class HeteroController extends Controller {

    /**
     * Lists all hetero semestre actual
     *
     * @Route("/pcurso/{pe}", name="hetero_index", methods={"GET"})
     * @Template("Hetero/index.html.twig")
     */
    public function indexAction($pe) {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Heterocursos')->findBy(array('semestre' => $pe));
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
        $datas = $em->getRepository('AppBundle:Heteroeval')->getPromedioescuela();


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
        $docentes = $em->getRepository('AppBundle:Docente')->findBy(array('periodo' => $pe, 'escuela' => $esc));
        $hetero = $em->getRepository('AppBundle:Heteroeval')->findBy(array('docente' => $docentes));
        $escuela = $em->getRepository('AppBundle:Escuela')->find($esc);
        return array(
            'hetero' => $hetero,
            'escuela' => $escuela,
            'pe' => $pe
        );
    }

}
