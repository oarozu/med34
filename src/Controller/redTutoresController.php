<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\redTutores;
use App\Form\redTutoresType;

/**
 * redTutores controller.
 *
 * @Route("/doc/redtutores")
 */
class redTutoresController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }
    /**
     * Lists all redTutores entities.
     *
     * @Route("/", name="redtutores", methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->doctrine->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('App:Docente')->find($session->get('docenteid'));
        $tutorias = $em->getRepository('App:Tutor')->findBy(array('docente' => $entity));

        return $this->render('redTutores/index.html.twig', array(
            'entity' => $entity,
            'tutorias' => $tutorias,
        ));
    }

    /**
     * @Route("/", name="redtutores_create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new redTutores();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('redtutores_show', array('id' => $entity->getId())));
        }

        return $this->render('redTutores/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a redTutores entity.
     *
     * @param redTutores $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(redTutores $entity)
    {
        $form = $this->createForm(redTutoresType::class, $entity, array(
            'action' => $this->generateUrl('redtutores_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new redTutores entity.
     *
     * @Route("/new", name="redtutores_new", methods={"GET"})
     */
    public function newAction()
    {
        $entity = new redTutores();
        $form = $this->createCreateForm($entity);

        return $this->render('redTutores/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a redTutores entity.
     *
     * @Route("/{id}", name="redtutores_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:redTutores')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find redTutores entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('redTutores/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing redTutores entity.
     *
     * @Route("/{id}/edit", name="redtutores_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:redTutores')->find($id);

        if (!$entity) {
            $entity = new redTutores();
            $em = $this->doctrine->getManager();
            $tutor = $em->getRepository('App:Tutor')->find($id);
            $entity->setId($tutor);
            $em->persist($entity);
            $em->flush();
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('redTutores/edit.html.twig', array(
            'evaluacion' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a redTutores entity.
     *
     * @param redTutores $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(redTutores $entity)
    {
        $form = $this->createForm(redTutoresType::class, $entity, array(
            'action' => $this->generateUrl('redtutores_update', array('id' => $entity->getId()->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing redTutores entity.
     *
     * @Route("/{id}", name="redtutores_update", methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:redTutores')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find redTutores entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $suma = 0;
        for ($i = 1; $i < 31; $i++) {
            $suma = $suma + $editForm["f" . $i]->getData();
        }
        $entity->setF0($suma / 30);
        $entity->setFecha(new \DateTime());

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('redtutores'));
        }
        return $this->render('redTutores/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a redTutores entity.
     *
     * @Route("/{id}", name="redtutores_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:redTutores')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find redTutores entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('redtutores'));
    }

    /**
     * Creates a form to delete a redTutores entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('redtutores_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }
}
