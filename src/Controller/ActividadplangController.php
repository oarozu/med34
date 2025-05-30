<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Actividadplang;
use App\Form\ActividadplangType;
use App\Form\ActividadDofeType;
use App\Form\ActividadplangAddType;

/**
 * Actividadplang controller.
 *
 * @Route("/doc/actividadplang")
 */
class ActividadplangController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Creates a new Actividadplang entity.
     * @Route("/", name="actividadplang_create",  methods={"POST"})
     */
    public function createAction(Request $request, $id)
    {
        $entity = new Actividadplang();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('actividadplang_show', array('id' => $entity->getId())));
        }

        return $this->render('Actividadplang/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Actividadplang entity.
     * @Route("/add/{id}", name="actividadplang_add",  methods={"POST"})
     */
    public function addAction(Request $request, $id)
    {
        $entity = new Actividadplang();
        $em = $this->doctrine->getManager();
        $plang = $em->getRepository('App:Plangestion')->find($id);
        $entity->setPlang($plang);
        $form = $this->createAddForm($entity, $id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('plangestion_crear', array('id' => $id)));
        }

        return $this->render('Actividadplang/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'id' => $id,
        ));
    }

    /**
     * Creates a form to create a Actividadplang entity.
     *
     * @param Actividadplang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Actividadplang $entity)
    {
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
    private function createAddForm(Actividadplang $entity, $id)
    {
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
     * @Route("/new/{id}/{ida}", name="actividadplang_new",  methods={"GET"})
     */
    public function newAction($id, $ida)
    {
        $entity = new Actividadplang();
        $em = $this->doctrine->getManager();
        $plang = $em->getRepository('App:Plangestion')->find($id);
        $actividad = $em->getRepository('App:Actividadrol')->find($ida);
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
     * @Route("/{id}", name="actividadplang_show",  methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Actividadplang')->find($id);

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
     * @Route("/{id}/edit", name="actividadplang_edit",  methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Actividadplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $borrarForm = $this->createBorrarForm($id);

        return $this->render('Actividadplang/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'borrar_form' => $borrarForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Actividadplang entity.
     *
     * @Route("/{id}/dofe", name="actividadplang_dofe",  methods={"GET"})
     */
    public function dofeAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Actividadplang')->find($id);

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
     * @Route("/borrar/{id}", name="actividadplang_borrar",  methods={"GET"})
     */
    public function borrarAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Actividadplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }
        $borrarForm = $this->createBorrarForm($id);

        return $this->render('Actividadplang/borrar.html.twig', array(
            'entity' => $entity,
            'borrar_form' => $borrarForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Actividadplang entity.
     *
     * @param Actividadplang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Actividadplang $entity)
    {
        $form = $this->createForm(ActividadplangType::class, $entity, array(
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
    private function createDofeForm(Actividadplang $entity)
    {
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
     * @Route("/{id}", name="actividadplang_update",  methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Actividadplang')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadplang entity.');
        }
        $entity->setPath(null);
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('plangestion_show', array('id' => $entity->getPlang()->getId())));
        }

        return $this->render('Actividadplang/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Actividadplang entity.
     *
     * @Route("/{id}/dofe", name="actividadplang_updatedofe",  methods={"PUT"})
     */
    public function updatedofeAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Actividadplang')->find($id);

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

        return $this->render('Actividadplang/dofe.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Delete Actividad
     *
     * @Route("/{id}/delete", name="actividadplang_delete",  methods={"GET"})
     */
    public function deleteAction(Request $request, $id)
    {

        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Actividadplang')->find($id);
        $session = $request->getSession();
        $docente = $em->getRepository('App:Docente')->find($session->get('docenteid'));
        if ($docente->getPlangestion() == $entity->getPlang()) {
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('plangestion_crear', array('id' => $docente->getId())));
    }

    /**
     * Deletes a Actividadplang entity.
     * @Route("/borrar/{id}", name="actividadplang_borrarreg",  methods={"DELETE"})
     */
    public function borrarregAction(Request $request, $id)
    {
        $form = $this->createBorrarForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $session = $request->getSession();
            $entity = $em->getRepository('App:Actividadplang')->find($id);
            $docente = $em->getRepository('App:Docente')->find($session->get('docenteid'));

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
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actividadplang_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * Creates a form to delete a Actividadplang entity by id.
     * @param mixed $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createBorrarForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actividadplang_borrar', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Borrar'))
            ->getForm();
    }

}
