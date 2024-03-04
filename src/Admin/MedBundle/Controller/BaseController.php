<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\Heteroeval;
use Admin\MedBundle\Entity\HeteroevalRepository;

/**
 * Base controller
 * @Route("/unad/doc")
 */
class BaseController extends Controller
{
    /**
     * Mostrar heteroevaluaciones
     * @Route("/{id}/heteroeval", name="heteroeval_info",  methods={"GET"})
     * @Template("Base/heteroeval.html.twig")
     */
    public function heteroevalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Docente')->find($id);
        $evaluaciones = $em->getRepository('AdminMedBundle:Heterocursos')->findBy(array('cedula' => $entity->getUser()->getId(), 'semestre' => $entity->getPeriodo()));

        if ($entity->getVinculacion() == 'DOFE') {
            $evaluaciones1 = $em->getRepository('AdminMedBundle:Heterocursos')->findBy(array('cedula' => $entity->getUser()->getId(), 'semestre' => $entity->getPeriodo() - 1));
            $evaluaciones = array_merge($evaluaciones1, $evaluaciones);
        }

        return array(
            'entity' => $entity,
            'evaluaciones' => $evaluaciones
        );
    }
}
