<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\Planmejoramiento;
use Admin\MedBundle\Form\PlanmejoramientoType;

/**
 * Planmejoramiento controller.
 *
 * @Route("/dec/planm")
 */
class PlanmejoramientoController extends Controller
{

    /**
     * Lists all Planmejoramiento entities.
     *
     * @Route("/", name="planmejoramiento")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entities = $em->getRepository('AdminMedBundle:Planmejoramiento')->findBy(array('autorid' => $session->get('escuelaid')));
        
        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Planmejoramiento entity.
     *
     * @Route("/", name="planmejoramiento_create")
     * @Method("POST")
     * @Template("AdminMedBundle:Planmejoramiento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Planmejoramiento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('planmejoramiento_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Planmejoramiento entity.
    *
    * @param Planmejoramiento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Planmejoramiento $entity)
    {
        $form = $this->createForm(new PlanmejoramientoType(), $entity, array(
            'action' => $this->generateUrl('planmejoramiento_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Planmejoramiento entity.
     *
     * @Route("/new", name="planmejoramiento_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Planmejoramiento();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    
        /**
     * Seleccionar docente
     *
     * @Route("/add", name="planmejoramiento_add")
     * @Method("GET")
     * @Template()
     */
    public function addAction(Request $request)
    {
       $em = $this->getDoctrine()->getManager();
       $session = $request->getSession();
       $escuela = $em->getRepository('AdminUnadBundle:Escuela')->find($session->get('escuelaid'));
       $entities = $em->getRepository('AdminUnadBundle:Docente')->findBy(array('escuela' => $escuela));
        return array(
        'entities' => $entities,
        );
    }
    
     /**
     * Agregar Plan Mejoramiento a Docente
     *
     * @Route("/add/{id}", name="planmejoramiento_agregar")
     * @Method("GET")
     * @Template()
     */
    public function agregarAction(Request $request, $id)
    {
       $planm = new Planmejoramiento();
       $em = $this->getDoctrine()->getManager();
       $session = $request->getSession();
       $docente = $em->getRepository('AdminUnadBundle:Docente')->findOneBy(array('id' => $id));
       $planm->setDocente($docente);
       $planm->setFechaCreacion(new \DateTime());
       $planm->setAutorid($session->get('escuelaid'));
       $em->persist($planm);
       $em->flush();
       
      return $this->redirect($this->generateUrl('planmejoramiento'));
  
    } 
    
    
    /**
     * Finds and displays a Planmejoramiento entity.
     *
     * @Route("/{id}", name="planmejoramiento_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Planmejoramiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Planmejoramiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    
     /**
     * Finds and displays a Planmejoramiento entity.
     *
     * @Method("GET")
     * @Template()
     */
    public function docAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Planmejoramiento')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Planmejoramiento entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Planmejoramiento entity.
     *
     * @Route("/{id}/edit", name="planmejoramiento_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Planmejoramiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Planmejoramiento entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Planmejoramiento entity.
    *
    * @param Planmejoramiento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Planmejoramiento $entity)
    {
        $form = $this->createForm(new PlanmejoramientoType(), $entity, array(
            'action' => $this->generateUrl('planmejoramiento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Planmejoramiento entity.
     *
     * @Route("/{id}", name="planmejoramiento_update")
     * @Method("PUT")
     * @Template("AdminMedBundle:Planmejoramiento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Planmejoramiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Planmejoramiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $entity->setFechaCierre(new \DateTime());
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('planmejoramiento_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Planmejoramiento entity.
     *
     * @Route("/{id}", name="planmejoramiento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:Planmejoramiento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Planmejoramiento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('planmejoramiento'));
    }

    /**
     * Creates a form to delete a Planmejoramiento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('planmejoramiento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Borrar Plan'))
            ->getForm()
        ;
    }
}
