<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\Actividadrol;
use Admin\MedBundle\Form\ActividadrolType;

/**
 * Actividadrol controller.
 *
 * @Route("/doc/actividadrol")
 */
class ActividadrolController extends Controller
{

    /**
     * Lists all Actividadrol entities.
     * @Route("/", name="actividadrol")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMedBundle:Actividadrol')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
        /**
     * Lists all Actividadrol entities.
     * @Route("/select/{id}", name="actividadrol_select")
     * @Method("GET")
     * @Template()
     */
    public function selectAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMedBundle:Actividadrol')->decarrera();
        $roles = $em->getRepository('AdminMedBundle:Rolacademico')->findAll();
        $periodo = $em->getRepository('AdminMedBundle:Periodoe')->findOneBy(array('id' => $this->container->getParameter('appmed.periodo') ));
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
     * @Route("/", name="actividadrol_create")
     * @Method("POST")
     * @Template("AdminMedBundle:Actividadrol:new.html.twig")
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
        $form = $this->createForm(new ActividadrolType(), $entity, array(
            'action' => $this->generateUrl('actividadrol_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Actividadrol entity.
     *
     * @Route("/new", name="actividadrol_new")
     * @Method("GET")
     * @Template()
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
     * @Route("/{id}", name="actividadrol_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Actividadrol')->find($id);

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
     * @Route("/{id}/edit", name="actividadrol_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Actividadrol')->find($id);

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
        $form = $this->createForm(new ActividadrolType(), $entity, array(
            'action' => $this->generateUrl('actividadrol_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Actividadrol entity.
     *
     * @Route("/{id}", name="actividadrol_update")
     * @Method("PUT")
     * @Template("AdminMedBundle:Actividadrol:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Actividadrol')->find($id);

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
     * @Route("/{id}", name="actividadrol_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:Actividadrol')->find($id);

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
