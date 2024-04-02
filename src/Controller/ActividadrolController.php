<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Entity\Actividadrol;
use App\Form\ActividadrolType;

/**
 * Actividadrol controller.
 *
 * @Route("/doc/actividadrol")
 */
class ActividadrolController extends AbstractController
{

    /**
     * Lists all Actividadrol entities.
     * @Route("/", name="actividadrol", methods={"GET"})
     * @Template("Actividadrol/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('App:Actividadrol')->findAll();

        return array(
            'entities' => $entities,
        );
    }

        /**
     * Lists all Actividadrol entities.
     * @Route("/select/{id}", name="actividadrol_select", methods={"GET"})
     * @Template("Actividadrol/select.html.twig")
     */
    public function selectAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('App:Actividadrol')->decarrera();
        $roles = $em->getRepository('App:Rolacademico')->findAll();
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $this->getParameter('appmed.periodo') ));
        return array(
            'entities' => $entities,
            'id'  => $id,
            'roles' => $roles,
            'periodo' => $periodo
        );
    }

    /**
     * Creates a new Actividadrol entity.
     *
     * @Route("/", name="actividadrol_create", methods={"POST"})
     * @Template("Actividadrol/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Actividadrol();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('actividadrol_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Actividadrol entity.
    *
    * @param Actividadrol $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Actividadrol $entity)
    {
        $form = $this->createForm(ActividadrolType::class, $entity, array(
            'action' => $this->generateUrl('actividadrol_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Actividadrol entity.
     *
     * @Route("/new", name="actividadrol_new", methods={"GET"})
     * @Template("Actividadrol/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Actividadrol();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Actividadrol entity.
     *
     * @Route("/{id}", name="actividadrol_show", methods={"GET"})
     * @Template("Actividadrol/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:Actividadrol')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadrol entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Actividadrol entity.
     *
     * @Route("/{id}/edit", name="actividadrol_edit", methods={"GET"})
     * @Template("Actividadrol/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:Actividadrol')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadrol entity.');
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
    * Creates a form to edit a Actividadrol entity.
    *
    * @param Actividadrol $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Actividadrol $entity)
    {
        $form = $this->createForm(ActividadrolType::class, $entity, array(
            'action' => $this->generateUrl('actividadrol_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Actividadrol entity.
     *
     * @Route("/{id}", name="actividadrol_update", methods={"PUT"})
     * @Template("Actividadrol/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:Actividadrol')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividadrol entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('actividadrol_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Actividadrol entity.
     *
     * @Route("/{id}", name="actividadrol_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('App:Actividadrol')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Actividadrol entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('actividadrol'));
    }

    /**
     * Creates a form to delete a Actividadrol entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actividadrol_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
