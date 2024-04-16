<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\formatoPlang;
use App\Form\formatoPlangType;

/**
 * formatoPlang controller.
 *
 * @Route("/doc/formatoplang")
 */
class formatoPlangController extends AbstractController
{


    /**
     * Creates a new formatoPlang entity.
     *
     * @Route("/crear/{id}", name="formatoplang_create", methods={"POST"})
     */
    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $docente = $em->getRepository('App:Docente')->find($id);
        $plan = $em->getRepository('App:Plangestion')->findOneBy(array('docente' => $docente));
        $entity = new formatoPlang();
        $entity->setPlan($plan);
        $form = $this->createCreateForm($entity, $id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('docente_show', array('id' => $id)));
        }

        return $this->render('formatoPlang/new.html.twig',  array(
            'entity' => $entity,
            'id'      => $id,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a formatoPlang entity.
    *
    * @param formatoPlang $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(formatoPlang $entity, $id)
    {
        $form = $this->createForm(formatoPlangType::class, $entity, array(
            'action' => $this->generateUrl('formatoplang_create', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Cargar'));

        return $form;
    }

    /**
     * Displays a form to create a new formatoPlang entity.
     *
     * @Route("/new/{id}", name="formatoplang_new", methods={"GET"})
     */
    public function newAction($id)
    {

        $entity = new formatoPlang();

        $form   = $this->createCreateForm($entity,$id);

        return $this->render('formatoPlang/new.html.twig', array(
            'entity' => $entity,
            'id'    => $id,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a formatoPlang entity.
     *
     * @Route("/{id}", name="formatoplang_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:formatoPlang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find formatoPlang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('formatoPlang/show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing formatoPlang entity.
     *
     * @Route("/{id}/edit", name="formatoplang_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:formatoPlang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find formatoPlang entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('formatoPlang/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a formatoPlang entity.
    *
    * @param formatoPlang $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(formatoPlang $entity)
    {
        $form = $this->createForm(formatoPlangType::class, $entity, array(
            'action' => $this->generateUrl('formatoplang_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing formatoPlang entity.
     *
     * @Route("/{id}", name="formatoplang_update", methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:formatoPlang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find formatoPlang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('formatoplang_edit', array('id' => $id)));
        }

        return $this->render('formatoPlang/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a formatoPlang entity.
     *
     * @Route("/{id}", name="formatoplang_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('App:formatoPlang')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find formatoPlang entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('formatoplang'));
    }

    /**
     * Creates a form to delete a formatoPlang entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('formatoplang_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
