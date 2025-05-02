<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\coevalTutor;
use App\Form\coevalTutorType;

/**
 * coevalTutor controller.
 *
 * @Route("/doc/coevaltutor")
 */
class coevalTutorController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Creates a new coevalTutor entity.
     *
     * @Route("/", name="coevaltutor_create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new coevalTutor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('coevaltutor_show', array('id' => $entity->getId())));
        }

        return $this->render('coevalTutor/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
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
     */
    public function newAction()
    {
        $entity = new coevalTutor();
        $form = $this->createCreateForm($entity);

        return $this->render('coevalTutor/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a coevalTutor entity.
     *
     * @Route("/{id}", name="coevaltutor_show", methods={"POST"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:coevalTutor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find coevalTutor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('coevalTutor/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing coevalTutor entity.
     *
     * @Route("/{id}/edit", name="coevaltutor_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:coevalTutor')->find($id);

        if (!$entity) {
            $entity = new coevalTutor();
            $em = $this->doctrine->getManager();
            $tutor = $em->getRepository('App:Tutor')->find($id);
            $entity->setTutor($tutor);
            $em->persist($entity);
            $em->flush();
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('coevalTutor/edit.html.twig', array(
            'coeval' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:coevalTutor')->find($id);

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

        return $this->render('coevalTutor/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        ));
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
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:coevalTutor')->find($id);

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
