<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Heteroeval;
use App\Entity\HeteroevalRepository;

/**
 * Base controller
 * @Route("/unad/doc")
 */
class BaseController extends AbstractController
{
    /**
     * Mostrar heteroevaluaciones
     * @Route("/{id}/heteroeval", name="heteroeval_info",  methods={"GET"})
     */
    public function heteroevalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:Docente')->find($id);
        $evaluaciones = $em->getRepository('App:Heterocursos')->findBy(array('cedula' => $entity->getUser()->getId(), 'semestre' => $entity->getPeriodo()));

        if ($entity->getVinculacion() == 'DOFE') {
            $evaluaciones1 = $em->getRepository('App:Heterocursos')->findBy(array('cedula' => $entity->getUser()->getId(), 'semestre' => $entity->getPeriodo() - 1));
            $evaluaciones = array_merge($evaluaciones1, $evaluaciones);
        }

        return $this->render('Base/heteroeval.html.twig', array(
            'entity' => $entity,
            'evaluaciones' => $evaluaciones
        ));
    }
}
