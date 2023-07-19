<?php

namespace Admin\MedBundle\Controller;

use Admin\MedBundle\Entity\Rolplang;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\Plangestion;
use Admin\MedBundle\Entity\Actividadplang;
use Admin\MedBundle\Entity\Avalplang;
use Admin\MedBundle\Entity\actividadplanglRepository;
use Admin\MedBundle\Form\PlangestionType;

/**
 * Plangestion controller.
 *
 * @Route("/doc/plangestion")
 */
class PlangestionController extends Controller
{

    /**
     * Creates a new Plangestion entity.
     *
     * @Route("/", name="plangestion_create")
     * @Method("POST")
     * @Template("AdminMedBundle:Plangestion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Plangestion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('plangestion_show', array('id' => $entity->getId())));
        }
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Plangestion entity.
     *
     * @param Plangestion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Plangestion $entity)
    {
        $form = $this->createForm(PlangestionType::class, $entity, array(
            'action' => $this->generateUrl('plangestion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Plangestion entity.
     * @Route("/add", name="plangestion_add")
     * @Method("GET")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $entity = new Plangestion();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));
        if (!$docente) {
            throw $this->createNotFoundException('Docente no encontrado');
        }
        $plangestion = $em->getRepository('AdminMedBundle:Plangestion')->find($session->get('docenteid'));
        if ($plangestion) {
            throw $this->createNotFoundException('Plan ya creado');
        }

        $entity->setId($docente);
        $entity->setEstado(0);
        $entity->setFechaCreacion(new \Datetime());
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('docente_show', array('id' => $session->get('docenteid'))));
    }

    /**
     * @Route("/{id}", name="plangestion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Plangestion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entidad no encontrada');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * @Route("/{id}/dofe", name="plangestion_dofe")
     * @Method("GET")
     * @Template()
     */
    public function dofeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Plangestion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entidad no encontrada');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * @Route("/conf/plan", name="plangestion_conf")
     * @Method("GET")
     * @Template()
     */
    public function confAction(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $periodo = $em->getRepository('AdminMedBundle:Periodoe')->findOneBy(array('id' => $this->container->getParameter('appmed.periodo')));
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));
        $entity = $em->getRepository('AdminMedBundle:Plangestion')->findOneBy(array('docente' => $docente));
        $rol_tutor = $em->getRepository('AdminMedBundle:Rolacademico')->findBy(array('id' => 1));
        $estutor = $em->getRepository('AdminMedBundle:Rolplang')->findOneBy(array('rol' => $rol_tutor,'plang'=> $entity));
        $rol_director = $em->getRepository('AdminMedBundle:Rolacademico')->findOneBy(array('id' => 2));
        $esdirector = $em->getRepository('AdminMedBundle:Rolplang')->findOneBy(array('rol' => $rol_director,'plang'=> $entity));
        if (!$entity) {
            throw $this->createNotFoundException('Plangestion no encontrado');
        }

        if (count($entity->getActividades()) != 0) {
            return $this->redirect($this->generateUrl('plangestion_show', array('id' => $session->get('docenteid'))));
        }

        return array(
            'entity' => $entity,
            'estutor' => isset($estutor),
            'esdirector' => isset($esdirector),
            'periodo' => $periodo
        );
    }


    /**
     * @Route("/conf/plan/{id}", name="plangestion_conf_add")
     * @Method("GET")
     * @Template()
     */
    public function addRole(Request $request, $id)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));
        $plang = $em->getRepository('AdminMedBundle:Plangestion')->findOneBy(array('docente' => $docente));
        $rol_a = $em->getRepository('AdminMedBundle:Rolacademico')->findOneBy(array('id' => $id));
        $rol_plang = $em->getRepository('AdminMedBundle:Rolplang')->findOneBy(array('rol' => $rol_a, 'plang' => $plang));
        if (!isset($rol_plang)) {
            $rol_plang = new Rolplang();
            $rol_plang->setPlang($plang);
            $rol_plang->setRol($rol_a);
            $rol_plang->setDescripcion("auto asignación");
            $em->persist($rol_plang);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('plangestion_conf'));
    }

    /**
     * @Route("/agregar/roles", name="plangestion_crear")
     * @Method("GET")
     * @Template()
     */
    public function crearAction(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $periodo = $em->getRepository('AdminMedBundle:Periodoe')->findOneBy(array('id' => $this->container->getParameter('appmed.periodo')));
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));
        $entity = $em->getRepository('AdminMedBundle:Plangestion')->findOneBy(array('docente' => $docente));
        $actividades = $em->getRepository('AdminMedBundle:Actividadplang')->findBy(array('plang' => $entity), array('actividad' => 'ASC'));

        if (!$entity) {
            throw $this->createNotFoundException('Plan gestion no encontrado');
        }


        return array(
            'entity' => $entity,
            'actividades' => $actividades,
            'periodo' => $periodo
        );
    }

    /**
     * @Method("GET")
     * @Template()
     */
    public function infoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($id);
        $entity = $docente->getPlangestion();
        $periodo = $em->getRepository('AdminMedBundle:Periodoe')->findOneBy(array('id' => $this->container->getParameter('appmed.periodo')));
        if (!$entity) {
            throw $this->createNotFoundException('No se encuentra el plan.');
        }

        if ($docente->getVinculacion() == 'De Carrera') {
            return $this->render('AdminMedBundle:Plangestion:info.html.twig', array(
                'entity' => $entity,
                'docente' => $docente,
            ));
        } elseif ($docente->getVinculacion() == 'DOFE') {
            return $this->render('AdminMedBundle:Plangestion:plandofe.html.twig', array(
                'entity' => $entity,
                'docente' => $docente
            ));
        } else {
            return $this->render('AdminMedBundle:Plangestion:planactividades.html.twig', array('docente' => $docente,
                'entity' => $entity,
                'periodo' => $periodo));
        }
    }

    /**
     * @Method("GET")
     * @Template()
     */
    public function autoevalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($id);
        if (!$docente) {
            throw $this->createNotFoundException('No se encuentra docente entity.');
        }
        if ($docente->getVinculacion() == 'DOFE') {
            return $this->render('AdminMedBundle:Plangestion:registrodofe.html.twig', array(
                'entity' => $docente,
            ));
        } else {
            return array(
                'entity' => $docente,
            );
        }
    }

    /**
     * Displays a form to edit an existing Plangestion entity.
     *
     * @Route("/{id}/edit", name="plangestion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Plangestion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plangestion entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Plangestion entity.
     *
     * @Route("/{id}/abrir", name="plangestion_abrir")
     * @Method("GET")
     * @Template()
     */
    public function abrirAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Plangestion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plangestion entity.');
        }
        $entity->setObservaciones(null);
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Plangestion entity.
     *
     * @param Plangestion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Plangestion $entity)
    {
        $form = $this->createForm(PlangestionType::class, $entity, array(
            'action' => $this->generateUrl('plangestion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Plangestion entity.
     *
     * @Route("/{id}", name="plangestion_update")
     * @Method("PUT")
     * @Template("AdminMedBundle:Plangestion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AdminMedBundle:Plangestion')->find($id);
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));
        $eval = $em->getRepository('AdminMedBundle:Evaluacion')->findOneBy(array('docente' => $docente));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plangestion entity.');
        }

        $actividades = $entity->getActividades();
        $suma = $aux = 0;
        foreach ($actividades as $actividad) {
            if ($actividad->getAutoevaluacion() > 0) {
                $suma = $suma + $actividad->getAutoevaluacion();
                $aux++;
            }
        }
        $entity->setAutoevaluacion($suma / $aux);
        $eval->setAuto($suma / $aux);
        $entity->setFechaAutoevaluacion(new \DateTime());
        $entity->setEstado(10);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            if ($docente->getVinculacion() == 'DOFE') {
                return $this->redirect($this->generateUrl('plangestion_dofe', array(
                    'id' => $entity->getId()
                )));
            } else {
                return $this->redirect($this->generateUrl('plangestion_show', array(
                    'id' => $entity->getId()
                )));
            }
        }
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }


    /**
     * Abrir an existing Plangestion entity.
     *
     * @Route("/{id}/abrir", name="plangestion_abrir_registro")
     * @Method("PUT")
     * @Template("AdminMedBundle:Plangestion:edit.html.twig")
     */
    public function abrirRegistroAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AdminMedBundle:Plangestion')->find($id);
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plangestion entity.');
        }

        $entity->setAutoevaluacion(null);
        $entity->setEstado(5);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            if ($docente->getVinculacion() == 'DOFE') {
                return $this->redirect($this->generateUrl('plangestion_dofe', array(
                    'id' => $entity->getId()
                )));
            } else {
                return $this->redirect($this->generateUrl('plangestion_show', array(
                    'id' => $entity->getId()
                )));
            }
        }
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }


    /** Cerrar plan
     * @Route("/{id}/cerrar", name="plangestion_cerrar")
     * @Method("GET")
     */
    public function cerrarAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminMedBundle:Plangestion')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Entidad No encontrada');
        }

        $entity->setFechaCierre(new \DateTime());
        $entity->setEstado(1);
        $em->flush();
        return $this->redirect($this->generateUrl('plangestion_crear'));
    }

    /** Confirmar plan
     * @Route("/{id}/confirm", name="plangestion_confirm")
     * @Method("GET")
     */
    public function confirmAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminMedBundle:Plangestion')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Entidad No encontrada');
        }
        if (count($entity->getActividades()) == 0) {
            foreach ($entity->getRoles() as $rol) {
                foreach ($rol->getRol()->getActividades() as $actividad) {
                    $actividadplan = new Actividadplang();
                    $actividadplan->setPlang($entity);
                    $actividadplan->setActividad($actividad);
                    if ($rol->getRol()->getId() == 13) {
                        $actividadplan->setDescripcion($rol->getDescripcion());
                    }
                    $em->persist($actividadplan);
                }
            }

            $entity->setEstado(5);
            $em->flush();
            return $this->redirect($this->generateUrl('plangestion_show', array('id' => $id)));
        } else {
            return $this->redirect($this->generateUrl('plangestion_show', array('id' => $id)));
        }
    }

    /**
     * Deletes a Plangestion entity.
     *
     * @Route("/{id}", name="plangestion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:Plangestion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Plangestion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('plangestion'));
    }

    /**
     * Creates a form to delete a Plangestion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('plangestion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    public function addAvales(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));
        //agregar avalador Decano N
        $aval = new Avalplang();
        $aval->setPlan($docente->getPlangestion());
        $aval->setUser($docente->getEscuela()->getDecano());
        $aval->setPerfil('DECN');
        $aval->setPeriodo($this->container->getParameter('appmed.periodo'));
        $em->persist($aval);
        //agregar avalador Director de Zona
        if ($docente->getCentro()->getId() != 89999) {
            $aval1 = new Avalplang();
            $aval1->setPlan($docente->getPlangestion());
            $aval1->setUser($docente->getCentro()->getZona()->getDirector());
            $aval1->setPerfil('DIRZ');
            $aval1->setPeriodo($this->container->getParameter('appmed.periodo'));
            $em->persist($aval1);
        }
        $em->flush();
    }
}
