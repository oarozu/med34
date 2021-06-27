<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\evalDofe;
use Admin\MedBundle\Entity\RedDofe;
use Admin\MedBundle\Form\CalificarDofeType;

/**
 * Base controller
 * @Route("/dofe")
 */
class DofeController extends Controller {

    /**
     * Lists all Escuela entities.
     *
     * @Route("/", name="dofe_index")
     * @Method("GET")
     * @Template("Dofe/index.html.twig")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();


        $entities = $em->getRepository('AdminMedBundle:RedDofe')->findAll();

        return array(
            'entities' => $entities
        );
    }

    /**
     * Lists all evaluaciones dofe entities
     * @Route("/evaluar", name="dofe_evaluar")
     * @Method("GET")
     * @Template("Dofe/evaluar.html.twig")
     */
    public function evaluarAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AdminMedBundle:RedDofe')->findBy(array('evaluador' => $this->getUser(), 'periodo'=> $this->container->getParameter('appmed.periodo')));
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all evaluaciones dofe entities
     * @Route("/eval/{id}", name="dofe_eval")
     * @Method("GET")
     * @Template("Dofe/eval.html.twig")
     */
    public function evalAction($id) {
        $em = $this->getDoctrine()->getManager();
        $evaluacion = $em->getRepository('AdminMedBundle:RedDofe')->findOneBy(array('id' => $id));
        $actividades = $em->getRepository('AdminMedBundle:evalDofe')->findBy(array('evaluacion' => $evaluacion));
        return array(
            'entity' => $evaluacion,
            'actividades' => $actividades
        );
    }

    /**
     * @Route("/calificar/{id}", name="dofe_calificaredit")
     * @Method("GET")
     * @Template("Dofe/calificar.html.twig")
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:evalDofe')->find($id);
        $eval = $entity->getEvaluacion();
        $docente = $entity->getEvaluacion()->getDocente();

        $actividad = $em->getRepository('AdminMedBundle:Actividadplang')->findOneBy(array('plang' => $docente->getPlangestion(), 'actividad' => $entity->getActividad()));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'eval' => $eval,
            'edit_form' => $editForm->createView(),
            'actividad' => $actividad
        );
    }

    /**
     * Edits an existing Actividadplang entity.
     * @Route("/calificar/{id}", name="dofe_calificarupdate")
     * @Method("PUT")
     * @Template("Dofe/calificar.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminMedBundle:evalDofe')->find($id);
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
        return array(
            'entity' => $entity,
            'eval' => $eval,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     * Creates a form to edit a Actividadplang entity.
     * @param Actividadplang $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(evalDofe $entity) {
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
     * @Route("/cerrar/{id}", name="dofe_cerrar")
     * @Method("GET")
     * @Template("Dofe/calificar.html.twig")
     */
    public function cerrarAction( $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminMedBundle:RedDofe')->findOneBy(array('id' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plangestion entity.');
        }
        $actividades = $em->getRepository('AdminMedBundle:evalDofe')->findBy(array('evaluacion' => $entity));
        $suma = $aux = 0;
        foreach ($actividades as $actividad) {
                $suma = $suma + $actividad->getCalificacion();
                $aux++;
        }

        //$editForm = $this->createCerrarForm($entity);
        //$editForm->handleRequest($request);
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
    private function createCerrarForm(Plangestion $entity) {
        $form = $this->createForm(DofeType::class, $entity, array(
            'action' => $this->generateUrl('dofe_cerrar', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));
        return $form;
    }
}
