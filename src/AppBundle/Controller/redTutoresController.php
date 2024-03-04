<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\redTutores;
use AppBundle\Form\redTutoresType;

/**
 * redTutores controller.
 *
 * @Route("/doc/redtutores")
 */
class redTutoresController extends Controller
{
    /**
     * Lists all redTutores entities.
     *
     * @Route("/", name="redtutores", methods={"GET"})
     * @Template("redTutores/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AppBundle:Docente')->find($session->get('docenteid'));
        $tutorias = $em->getRepository('AppBundle:Tutor')->findBy(array('docente' => $entity));

        return array(
            'entity' => $entity,
            'tutorias' => $tutorias,
        );
    }

    /**
     * @Route("/", name="redtutores_create", methods={"POST"})
     * @Template("redTutores/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new redTutores();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('redtutores_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
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
     * @Template("redTutores/new.html.twig")
     */
    public function newAction()
    {
        $entity = new redTutores();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a redTutores entity.
     *
     * @Route("/{id}", name="redtutores_show", methods={"GET"})
     * @Template("redTutores/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:redTutores')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find redTutores entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing redTutores entity.
     *
     * @Route("/{id}/edit", name="redtutores_edit", methods={"GET"})
     * @Template("redTutores/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:redTutores')->find($id);

        if (!$entity) {
            $entity = new redTutores();
            $em = $this->getDoctrine()->getManager();
            $tutor = $em->getRepository('AppBundle:Tutor')->find($id);
            $entity->setId($tutor);
            $em->persist($entity);
            $em->flush();
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'evaluacion' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
     * @Template("redTutores/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:redTutores')->find($id);

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
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
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
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:redTutores')->find($id);

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
