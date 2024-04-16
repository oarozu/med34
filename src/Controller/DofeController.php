<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\evalDofe;
use App\Entity\RedDofe;
use App\Form\CalificarDofeType;

/**
 * Base controller
 * @Route("/dofe")
 */
class DofeController extends AbstractController
{

    /**
     * Lists all Escuela entities.
     *
     * @Route("/", name="dofe_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('App:RedDofe')->findBy(array('periodo' => '20236'));
        return $this->render('Dofe/index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Lists all evaluaciones dofe entities
     * @Route("/evaluar", name="dofe_evaluar", methods={"GET"})
     */
    public function evaluarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('App:RedDofe')->findBy(array('evaluador' => $this->getUser(), 'periodo' => $this->getParameter('appmed.periodo')));
        return $this->render('Dofe/evaluar.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Lists all evaluaciones dofe entities
     * @Route("/eval/{id}", name="dofe_eval", methods={"GET"})
     */
    public function evalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $evaluacion = $em->getRepository('App:RedDofe')->findOneBy(array('id' => $id));
        $actividades = $em->getRepository('App:evalDofe')->findBy(array('evaluacion' => $evaluacion));
        if (count($actividades) == 0) {
            $n_actividades = $em->getRepository('App:evalDofe')->getActividades($id);
            foreach ($n_actividades as $actividad) {
                $entity = new evalDofe();
                $eval = $em->getRepository('App:RedDofe')->findOneBy(array('id' => $actividad['id']));
                $entity->setEvaluacion($eval);
                $actPlang = $em->getRepository('App:Actividadrol')->findOneBy(array('id' => $actividad['actividad_id']));
                $entity->setActividad($actPlang);
                $em->persist($entity);
            }
            $em->flush();
            return $this->redirect($this->generateUrl('dofe_eval', array('id' => $id)));

        }
        return $this->render('Dofe/eval.html.twig', array(
            'entity' => $evaluacion,
            'actividades' => $actividades
        ));
    }

    /**
     * @Route("/calificar/{id}", name="dofe_calificaredit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:evalDofe')->find($id);
        $eval = $entity->getEvaluacion();
        $docente = $entity->getEvaluacion()->getDocente();

        $actividad = $em->getRepository('App:Actividadplang')->findOneBy(array('plang' => $docente->getPlangestion(), 'actividad' => $entity->getActividad()));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('Dofe/calificar.html.twig', array(
            'entity' => $entity,
            'eval' => $eval,
            'edit_form' => $editForm->createView(),
            'actividad' => $actividad
        ));
    }

    /**
     * Edits an existing Actividadplang entity.
     * @Route("/calificar/{id}", name="dofe_calificarupdate", methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:evalDofe')->find($id);
        $eval = $entity->getEvaluacion();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }
        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('dofe_eval', array('id' => $entity->getEvaluacion()->getId())));
        }
        return $this->render('Dofe/calificar.html.twig', array(
            'entity' => $entity,
            'eval' => $eval,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Creates a form to edit a Actividadplang entity.
     * @param Actividadplang $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(evalDofe $entity)
    {
        $form = $this->createForm(CalificarDofeType::class, $entity, array(
            //  'action' => $this->generateUrl('dofe_calificarupdate', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing reddofe entity.
     *
     * @Route("/cerrar/{id}", name="dofe_cerrar", methods={"GET"})
     */
    public function cerrarAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:RedDofe')->findOneBy(array('id' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plangestion entity.');
        }
        $actividades = $em->getRepository('App:evalDofe')->findBy(array('evaluacion' => $entity));
        $suma = $aux = 0;
        foreach ($actividades as $actividad) {
            $suma = $suma + $actividad->getCalificacion();
            $aux++;
        }

        if ($entity) {
            $entity->setCalificacion($suma / $aux);
            $entity->setFecha(new \DateTime());
            $em->flush();
            return $this->redirect($this->generateUrl('dofe_evaluar', array(
                'id' => $entity->getId()
            )));
        }
    }

    /**
     * Creates a form to cerrar eval Dofe entity
     * @param Plangestion $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCerrarForm(Plangestion $entity)
    {
        $form = $this->createForm(DofeType::class, $entity, array(
            'action' => $this->generateUrl('dofe_cerrar', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));
        return $form;
    }
}
