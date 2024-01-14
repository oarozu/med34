<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\Rolacademico;
use Admin\MedBundle\Form\RolacademicoType;

/**
 * Rolacademico controller.
 *
 * @Route("/unad/rolacademico")
 */
class RolacademicoController extends Controller
{

    /**
     * Lists all Rolacademico entities.
     *
     * @Route("/", name="rolacademico", methods={"GET"})
     * @Template("AdminMedBundle:Rolacademico:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMedBundle:Rolacademico')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Rolacademico entity.
     *
     * @Route("/", name="rolacademico_create", methods={"POST"})
     * @Template("AdminMedBundle:Rolacademico:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Rolacademico();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rolacademico_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Rolacademico entity.
    *
    * @param Rolacademico $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Rolacademico $entity)
    {
        $form = $this->createForm(RolacademicoType::class, $entity, array(
            'action' => $this->generateUrl('rolacademico_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Rolacademico entity.
     *
     * @Route("/new", name="rolacademico_new", methods={"GET"})
     * @Template("AdminMedBundle:Rolacademico:new.html.twig")
     */
    public function newAction()
    {
        $entity = new Rolacademico();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Rolacademico entity.
     *
     * @Route("/{id}", name="rolacademico_show", methods={"GET"})
     * @Template("AdminMedBundle:Rolacademico:show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Rolacademico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rolacademico entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Rolacademico entity.
     *
     * @Route("/{id}/edit", name="rolacademico_edit", methods={"GET"})
     * @Template("AdminMedBundle:Rolacademico:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Rolacademico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rolacademico entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Rolacademico entity.
    *
    * @param Rolacademico $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Rolacademico $entity)
    {
        $form = $this->createForm(RolacademicoType::class, $entity, array(
            'action' => $this->generateUrl('rolacademico_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Rolacademico entity.
     *
     * @Route("/{id}", name="rolacademico_update", methods={"PUT"})
     * @Template("AdminMedBundle:Rolacademico:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Rolacademico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rolacademico entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('rolacademico_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Rolacademico entity.
     *
     * @Route("/{id}", name="rolacademico_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:Rolacademico')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rolacademico entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rolacademico'));
    }

    /**
     * Creates a form to delete a Rolacademico entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rolacademico_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
