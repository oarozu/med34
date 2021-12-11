<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\Actividadplang;
use Admin\MedBundle\Form\ActividadplangType;
use Admin\MedBundle\Form\ActividadDofeType;
use Admin\MedBundle\Form\ActividadplangAddType;

/**
 * Actividadplang controller.
 *
 * @Route("/doc/actividadplang")
 */
class ActividadplangController extends Controller {

    /**
     * Lists all Actividadplang entities.
     *
     * @Route("/", name="actividadplang")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMedBundle:Actividadplang')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Actividadplang entity.
     * @Route("/", name="actividadplang_create")
     * @Method("POST")
     * @Template("AdminMedBundle:Actividadplang:new.html.twig")
     */
    public function createAction(Request $request, $id) {
        $entity = new Actividadplang();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('actividadplang_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Actividadplang entity.
     * @Route("/add/{id}", name="actividadplang_add")
     * @Method("POST")
     * @Template("AdminMedBundle:Actividadplang:new.html.twig")
     */
    public function addAction(Request $request, $id) {
        $entity = new Actividadplang();
        $em = $this->getDoctrine()->getManager();
        $plang = $em->getRepository('AdminMedBundle:Plangestion')->find($id);
        #$actividad = $em->getRepository('AdminMedBundle:Actividadplang')->find($ida);
        $entity->setPlang($plang);
        $form = $this->createAddForm($entity, $id);
        $form->handleRequest($request);

        # $actividad = $em->getRepository('AdminMedBundle:Actividadrol')->find($form->get('actividad')->getData());
        #$entity->setActividad($actividad);


        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('plangestion_crear', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'id' => $id,
        );
    }

    /**
     * Creates a form to create a Actividadplang entity.
     *
     * @param Actividadplang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Actividadplang $entity) {
        $form = $this->createForm(ActividadplangType::class, $entity, array(
            'action' => $this->generateUrl('actividadplang_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a form to create a Actividadplang entity.
     *
     * @param Actividadplang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createAddForm(Actividadplang $entity, $id) {
        $form = $this->createForm(ActividadplangAddType::class, $entity, array(
            'action' => $this->generateUrl('actividadplang_add', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new Actividadplang entity.
     *
     * @Route("/new/{id}/{ida}", name="actividadplang_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id, $ida) {
        $entity = new Actividadplang();
        $em = $this->getDoctrine()->getManager();
        $plang = $em->getRepository('AdminMedBundle:Plangestion')->find($id);
        $actividad = $em->getRepository('AdminMedBundle:Actividadrol')->find($ida);
        $entity->setActividad($actividad);
        $entity->setPlang($plang);
        $form = $this->createAddForm($entity, $id);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'id' => $id,
        );
    }

    /**
     * Finds and displays a Actividadplang entity.
     *
     * @Route("/{id}", name="actividadplang_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Actividadplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Actividadplang entity.
     *
     * @Route("/{id}/edit", name="actividadplang_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Actividadplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $borrarForm = $this->createBorrarForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'borrar_form' => $borrarForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Actividadplang entity.
     *
     * @Route("/{id}/dofe", name="actividadplang_dofe")
     * @Method("GET")
     * @Template()
     */
    public function dofeAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Actividadplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }

        $editForm = $this->createDofeForm($entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     *
     * @Route("/borrar/{id}", name="actividadplang_borrar")
     * @Method("GET")
     * @Template()
     */
    public function borrarAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Actividadplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }
        $borrarForm = $this->createBorrarForm($id);

        return array(
            'entity' => $entity,
            'borrar_form' => $borrarForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Actividadplang entity.
     *
     * @param Actividadplang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Actividadplang $entity) {
        $form = $this->createForm( ActividadplangType::class, $entity, array(
            'action' => $this->generateUrl('actividadplang_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Update'));
        return $form;
    }

    /**
     * Creates a form to edit a Actividadplang entity.
     *
     * @param Actividadplang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDofeForm(Actividadplang $entity) {
        $form = $this->createForm(ActividadDofeType::class, $entity, array(
            'action' => $this->generateUrl('actividadplang_updatedofe', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing Actividadplang entity.
     *
     * @Route("/{id}", name="actividadplang_update")
     * @Method("PUT")
     * @Template("AdminMedBundle:Actividadplang:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Actividadplang')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }
        $entity->setPath(null);
        //$em->flush();
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('plangestion_show', array('id' => $entity->getPlang()->getId())));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Actividadplang entity.
     *
     * @Route("/{id}/dofe", name="actividadplang_updatedofe")
     * @Method("PUT")
     * @Template("AdminMedBundle:Actividadplang:regitro-dofe-edit.html.twig")
     */
    public function updatedofeAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Actividadplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createDofeForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('plangestion_dofe', array('id' => $entity->getPlang()->getId())));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Delete Actividad
     *
     * @Route("/{id}/delete", name="actividadplang_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminMedBundle:Actividadplang')->find($id);
        $session = $request->getSession();
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));
        if ($docente->getPlangestion() == $entity->getPlang()) {
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('plangestion_crear', array('id' => $docente->getId())));
    }

    /**
     * Deletes a Actividadplang entity.
     * @Route("/borrar/{id}", name="actividadplang_borrarreg")
     * @Method("DELETE")
     */
    public function borrarregAction(Request $request, $id) {
        $form = $this->createBorrarForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $session = $request->getSession();
            $entity = $em->getRepository('AdminMedBundle:Actividadplang')->find($id);
            $docente = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Actividadplang entity.');
            }
            $entity->setObservaciones('');
            $entity->setAutoevaluacion(NULL);
            $entity->setPath(NULL);
            $entity->removeUpload();
            $em->flush();
        }

        if ($docente->getVinculacion() == 'DOFE') {
            return $this->redirect($this->generateUrl('plangestion_dofe', array('id' => $entity->getPlang()->getId())));
        } else {
            return $this->redirect($this->generateUrl('plangestion_show', array('id' => $entity->getPlang()->getId())));
        }
    }

    /**
     * Creates a form to delete a Actividadplang entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('actividadplang_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', SubmitType::class, array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    /**
     * Creates a form to delete a Actividadplang entity by id.
     * @param mixed $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createBorrarForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('actividadplang_borrar', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', SubmitType::class, array('label' => 'Borrar'))
                        ->getForm()
        ;
    }

}
