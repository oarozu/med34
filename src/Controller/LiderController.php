<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Lider controller.
 *
 * @Route("/lider")
 */
class LiderController extends AbstractController
{

    /**
     * Lists all cursos entities por escuela.
     *
     * @Route("/cursos/{id}", name="lider_cursos", methods={"GET"})
     * @Template("Curso/index.html.twig")
     *
     */
    public function porescuelaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $escuela = $em->getRepository('AppBundle:Escuela')->findOneBy(array('id' => $id));
        $sigla = $escuela->getSigla();
        $entities = $em->getRepository('AppBundle:Curso')->findBy(array('escuela' => $sigla));

        return array(
            'entities' => $entities,
            'sigla' => $sigla
        );
    }

    /**
     * Lists all cursos entities por escuela.
     *
     * @Route("/programas", name="lider_programas", methods={"GET"})
     * @Template("Curso/lider.html.twig")
     *
     */
    public function programasAction(Request $request)
    {
        $session = $request->getSession();

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $programas = $em->getRepository('AppBundle:Programa')->findBy(array('lider' => $user));
        $cursos = $em->getRepository('AppBundle:Curso')->findBy(array('programa' => $programas));
        $periodoe = $em->getRepository('AppBundle:Periodoe')->findBy(array('id' => $session->get('periodoe')));
        $periodoa = $em->getRepository('AdminMedBundle:Periodoa')->findOneBy(array('periodoe' => $periodoe));
        $ofertas = $em->getRepository('AdminMedBundle:Oferta')->findBy(array('curso' => $cursos, 'periodo' => $periodoa),array('director' => 'ASC'));

        return array(
            'programas' => $programas,
            'cursos' => $cursos,
            'ofertas' => $ofertas,
            'periodoe' => $periodoe,
            'periodoa' => $periodoa
        );
    }
}
