<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Lider controller.
 *
 * @Route("/lider")
 */
class LiderController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Lists all cursos entities por escuela.
     *
     * @Route("/cursos/{id}", name="lider_cursos", methods={"GET"})
     *
     */
    public function porescuelaAction($id)
    {
        $em = $this->doctrine->getManager();
        $escuela = $em->getRepository('App:Escuela')->findOneBy(array('id' => $id));
        $sigla = $escuela->getSigla();
        $entities = $em->getRepository('App:Curso')->findBy(array('escuela' => $sigla));


        return $this->render('Curso/index.html.twig',  array(
            'entities' => $entities,
            'sigla' => $sigla
        ));
    }

    /**
     * Lists all cursos entities por escuela.
     *
     * @Route("/programas", name="lider_programas", methods={"GET"})
     *
     */
    public function programasAction(Request $request)
    {
        $session = $request->getSession();

        $em = $this->doctrine->getManager();
        $user = $this->getUser();
        $programas = $em->getRepository('App:Programa')->findBy(array('lider' => $user));
        $cursos = $em->getRepository('App:Curso')->findBy(array('programa' => $programas));
        $periodoe = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));
        $periodoa = $em->getRepository('App:Periodoa')->findOneBy(array('periodoe' => $periodoe));
        $ofertas = $em->getRepository('App:Oferta')->findBy(array('curso' => $cursos, 'periodo' => $periodoa),array('director' => 'ASC'));

        return $this->render('Curso/lider.html.twig', array(
            'programas' => $programas,
            'cursos' => $cursos,
            'ofertas' => $ofertas,
            'periodoe' => $periodoe
        ));
    }
}
