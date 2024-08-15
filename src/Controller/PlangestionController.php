<?php

namespace App\Controller;

use App\Entity\Rolplang;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plangestion;
use App\Entity\Actividadplang;
use App\Entity\Avalplang;
use App\Form\PlangestionType;

/**
 * Plangestion controller.
 *
 * @Route("/doc/plangestion")
 */
class PlangestionController extends AbstractController
{

    /**
     * Creates a new Plangestion entity.
     *
     * @Route("/", name="plangestion_create", methods={"POST"})
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
        return $this->render('Plangestion/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Plangestion entity.
     *
     * @param Plangestion $entity The entity
     *
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
     * @Route("/add", name="plangestion_add", methods={"GET"})
     */
    public function addAction(Request $request)
    {
        $entity = new Plangestion();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $docente = $em->getRepository('App:Docente')->find($session->get('docenteid'));
        if (!$docente) {
            throw $this->createNotFoundException('Docente no encontrado');
        }
        $plangestion = $em->getRepository('App:Plangestion')->find($session->get('docenteid'));
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
     * @Route("/auto", name="plangestion_show", methods={"GET"})
     */
    public function showAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $periodoe_id = $session->get('periodoe');
        $periodoe = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $periodoe_id));
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $docente = $em->getRepository('App:Docente')->findOneBy(array('user' => $user, 'periodo' => $periodoe_id));
        $entity = $em->getRepository('App:Plangestion')->findOneBy(array('docente' => $docente));
        //$this->checkActividades($entity, 2);

        if (!$entity) {
            throw $this->createNotFoundException('Entidad no encontrada');
        }
        return $this->render('Plangestion/show.html.twig', array(
            'entity' => $entity,
            'periodo' => $periodoe
        ));
    }

    public function checkActividades($plang, $rolId){
        $em = $this->getDoctrine()->getManager();
        $rolAc = $em->getRepository('App:Rolacademico')->findOneBy(array('id' => $rolId));
        $roles = $em->getRepository('App:Rolplang')->findBy(array('plang' => $plang, 'rol' => $rolAc));
        if (count($roles) > 0){
        $actRol = $em->getRepository('App:Actividadrol')->findBy(array('rol' => $rolAc));
        $actPlan = $em->getRepository('App:Actividadplang')->findBy(array('plang' => $plang, 'actividad' => $actRol));
         if (count($actPlan) == 0){
            $this->addActividades($plang, $rolAc);
         }
        }
    }

    public function addActividades($plang, $rolAc){
        $em = $this->getDoctrine()->getManager();
        foreach ($rolAc->getActividades() as $actividad) {
            $actividadplan = new Actividadplang();
            $actividadplan->setPlang($plang);
            $actividadplan->setActividad($actividad);
            $em->persist($actividadplan);
        }
        $em->flush();
    }

    /**
     * @Route("/{id}/dofe", name="plangestion_dofe", methods={"GET"})
     */
    public function dofeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:Plangestion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entidad no encontrada');
        }
        return $this->render('Plangestion/dofe.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * @Route("/conf/plan", name="plangestion_conf", methods={"GET"})
     */
    public function confAction(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));
        $docente = $em->getRepository('App:Docente')->find($session->get('docenteid'));
        $entity = $em->getRepository('App:Plangestion')->findOneBy(array('docente' => $docente));
        $rol_tutor = $em->getRepository('App:Rolacademico')->findBy(array('id' => 1));
        $estutor = $em->getRepository('App:Rolplang')->findOneBy(array('rol' => $rol_tutor,'plang'=> $entity));
        $rol_director = $em->getRepository('App:Rolacademico')->findOneBy(array('id' => 2));
        $esdirector = $em->getRepository('App:Rolplang')->findOneBy(array('rol' => $rol_director,'plang'=> $entity));
        if (!$entity) {
            throw $this->createNotFoundException('Plangestion no encontrado');
        }

        if (count($entity->getActividades()) != 0) {
            return $this->redirect($this->generateUrl('plangestion_show', array('id' => $session->get('docenteid'))));
        }

        return $this->render('Plangestion/conf.html.twig', array(
            'entity' => $entity,
            'estutor' => isset($estutor),
            'esdirector' => isset($esdirector),
            'periodo' => $periodo
        ));
    }


    /**
     * @Route("/conf/plan/{id}", name="plangestion_conf_add", methods={"GET"})
     */
    public function addRole(Request $request, $id)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $docente = $em->getRepository('App:Docente')->find($session->get('docenteid'));
        $plang = $em->getRepository('App:Plangestion')->findOneBy(array('docente' => $docente));
        $rol_a = $em->getRepository('App:Rolacademico')->findOneBy(array('id' => $id));
        $rol_plang = $em->getRepository('App:Rolplang')->findOneBy(array('rol' => $rol_a, 'plang' => $plang));
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
     * @Route("/agregar/roles", name="plangestion_crear", methods={"GET"})
     */
    public function crearAction(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $docente = $em->getRepository('App:Docente')->find($session->get('docenteid'));
        $periodoe = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $docente->getPeriodo()));
        $entity = $em->getRepository('App:Plangestion')->findOneBy(array('docente' => $docente));
        $actividades = $em->getRepository('App:Actividadplang')->findBy(array('plang' => $entity), array('actividad' => 'ASC'));

        if (!$entity) {
            throw $this->createNotFoundException('Plan gestion no encontrado');
        }


        return $this->render('Plangestion/crear.html.twig', array(
            'entity' => $entity,
            'actividades' => $actividades,
            'periodo' => $periodoe
        ));
    }

    /**
     */
    public function infoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $docente = $em->getRepository('App:Docente')->find($id);
        $entity = $docente->getPlangestion();
        $periodoe = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $docente->getPeriodo()));
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $this->getParameter('appmed.periodo')));
        if (!$entity) {
            throw $this->createNotFoundException('No se encuentra el plan.');
        }

        if ($docente->getVinculacion() == 'De Carrera') {
            return $this->render('Plangestion/info.html.twig', array(
                'entity' => $entity,
                'docente' => $docente,
                'periodo' => $periodoe
            ));
        } elseif ($docente->getVinculacion() == 'DOFE') {
            return $this->render('Plangestion/plandofe.html.twig', array(
                'entity' => $entity,
                'docente' => $docente
            ));
        } else {
            return $this->render('Plangestion/planactividades.html.twig', array('docente' => $docente,
                'entity' => $entity,
                'periodo' => $periodo));
        }
    }

    /**
     */
    public function autoevalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $docente = $em->getRepository('App:Docente')->find($id);
        if (!$docente) {
            throw $this->createNotFoundException('No se encuentra docente entity.');
        }
        if ($docente->getVinculacion() == 'DOFE') {
            return $this->render('Plangestion/registrodofe.html.twig', array(
                'entity' => $docente,
            ));
        } else {
            return $this->render('Plangestion/autoeval.html.twig', array(
                'entity' => $docente,
            ));
        }
    }

    /**
     * Displays a form to edit an existing Plangestion entity.
     *
     * @Route("/{id}/edit", name="plangestion_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:Plangestion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plangestion entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Plangestion/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Plangestion entity.
     *
     * @Route("/{id}/abrir", name="plangestion_abrir", methods={"GET"})
     */
    public function abrirAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:Plangestion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plangestion entity.');
        }
        $entity->setObservaciones(null);
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Plangestion/abrir.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Plangestion entity.
     *
     * @param Plangestion $entity The entity
     *
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
     * @Route("/{id}", name="plangestion_update", methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('App:Plangestion')->find($id);
        $docenteid = $session->get('docenteid');
        if ($docenteid == null) {
            return $this->redirect($this->generateUrl('home_user_inicio'));
        }
        $docente = $em->getRepository('App:Docente')->find($docenteid);
        $eval = $em->getRepository('App:Evaluacion')->findOneBy(array('docente' => $docente));
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
        $eval->setAuto1($suma / $aux);
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
        return $this->render('Plangestion/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }


    /**
     * Abrir an existing Plangestion entity.
     *
     * @Route("/{id}/abrir", name="plangestion_abrir_registro", methods={"PUT"})
     */
    public function abrirRegistroAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('App:Plangestion')->find($id);
        $docente = $em->getRepository('App:Docente')->find($session->get('docenteid'));
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
        return $this->render('Plangestion/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }


    /** Cerrar plan
     * @Route("/{id}/cerrar", name="plangestion_cerrar", methods={"GET"})
     */
    public function cerrarAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:Plangestion')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Entidad No encontrada');
        }

        $entity->setFechaCierre(new \DateTime());
        $entity->setEstado(1);
        $em->flush();
        return $this->redirect($this->generateUrl('plangestion_crear'));
    }

    /** Confirmar plan
     * @Route("/{id}/confirm", name="plangestion_confirm", methods={"GET"})
     */
    public function confirmAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:Plangestion')->find($id);
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
     * @Route("/{id}", name="plangestion_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('App:Plangestion')->find($id);

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
        $docente = $em->getRepository('App:Docente')->find($session->get('docenteid'));
        //agregar avalador Decano N
        $aval = new Avalplang();
        $aval->setPlan($docente->getPlangestion());
        $aval->setUser($docente->getEscuela()->getDecano());
        $aval->setPerfil('DECN');
        $aval->setPeriodo($this->getParameter('appmed.periodo'));
        $em->persist($aval);
        //agregar avalador Director de Zona
        if ($docente->getCentro()->getId() != 89999) {
            $aval1 = new Avalplang();
            $aval1->setPlan($docente->getPlangestion());
            $aval1->setUser($docente->getCentro()->getZona()->getDirector());
            $aval1->setPerfil('DIRZ');
            $aval1->setPeriodo($this->getParameter('appmed.periodo'));
            $em->persist($aval1);
        }
        $em->flush();
    }
}
