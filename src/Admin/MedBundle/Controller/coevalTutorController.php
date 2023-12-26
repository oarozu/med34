<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\coevalTutor;
use Admin\MedBundle\Form\coevalTutorType;

/**
 * coevalTutor controller.
 *
 * @Route("/doc/coevaltutor")
 */
class coevalTutorController extends Controller
{

    /**
     * Creates a new coevalTutor entity.
     *
     * @Route("/", name="coevaltutor_create", methods={"POST"})
     * @Template("AdminMedBundle:coevalTutor:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new coevalTutor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('coevaltutor_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a coevalTutor entity.
     *
     * @param coevalTutor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(coevalTutor $entity)
    {
        $form = $this->createForm(coevalTutorType::class, $entity, array(
            'action' => $this->generateUrl('coevaltutor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new coevalTutor entity.
     *
     * @Route("/new", name="coevaltutor_new", methods={"GET"})
     * @Template("AdminMedBundle:coevalTutor:new.html.twig")
     */
    public function newAction()
    {
        $entity = new coevalTutor();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a coevalTutor entity.
     *
     * @Route("/{id}", name="coevaltutor_show", methods={"POST"})
     * @Template("AdminMedBundle:coevalTutor:show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:coevalTutor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find coevalTutor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing coevalTutor entity.
     *
     * @Route("/{id}/edit", name="coevaltutor_edit", methods={"GET"})
     * @Template("AdminMedBundle:coevalTutor:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:coevalTutor')->find($id);

        if (!$entity) {
            $entity = new coevalTutor();
            $em = $this->getDoctrine()->getManager();
            $tutor = $em->getRepository('AdminMedBundle:Tutor')->find($id);
            $entity->setTutor($tutor);
            $em->persist($entity);
            $em->flush();
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'coeval' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a coevalTutor entity.
     *
     * @param coevalTutor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(coevalTutor $entity)
    {
        $form = $this->createForm(coevalTutorType::class, $entity, array(
            'action' => $this->generateUrl('coevaltutor_update', array('id' => $entity->getTutor()->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing coevalTutor entity.
     *
     * @Route("/{id}", name="coevaltutor_update", methods={"PUT"})
     * @Template("AdminMedBundle:coevalTutor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:coevalTutor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find coevalTutor entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $entity->setFecha(new \DateTime());

        $suma = 0;
        $tot = 0;
        for ($i = 1; $i < 13; $i++) {
            if ($editForm["f" . $i]->getData() > 0) {
                $suma = $suma + $editForm["f" . $i]->getData();
                $tot = $tot + 1;
            }
        }
        $entity->setF0($suma / $tot);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('docente_coevaltutor'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     * Deletes a coevalTutor entity.
     *
     * @Route("/{id}", name="coevaltutor_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:coevalTutor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find coevalTutor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('coevaltutor'));
    }

    /**
     * Creates a form to delete a coevalTutor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('coevaltutor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }
}
