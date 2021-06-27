<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * Lists all coevalTutor entities.
     *
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMedBundle:coevalTutor')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new coevalTutor entity.
     *
     * @Route("/", name="coevaltutor_create")
     * @Method("POST")
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
            'form'   => $form->createView(),
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
        $form = $this->createForm(new coevalTutorType(), $entity, array(
            'action' => $this->generateUrl('coevaltutor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new coevalTutor entity.
     *
     * @Route("/new", name="coevaltutor_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new coevalTutor();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a coevalTutor entity.
     *
     * @Route("/{id}", name="coevaltutor_show")
     * @Method("GET")
     * @Template()
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
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing coevalTutor entity.
     *
     * @Route("/{id}/edit", name="coevaltutor_edit")
     * @Method("GET")
     * @Template()
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
        ##    throw $this->createNotFoundException('Entidad no encontrada');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'coeval'      => $entity,
            'edit_form'   => $editForm->createView(),
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
        $form = $this->createForm(new coevalTutorType(), $entity, array(
            'action' => $this->generateUrl('coevaltutor_update', array('id' => $entity->getTutor()->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing coevalTutor entity.
     *
     * @Route("/{id}", name="coevaltutor_update")
     * @Method("PUT")
     * @Template("AdminMedBundle:coevalTutor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:coevalTutor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find coevalTutor entity.');
        }

       // $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        $entity->setFecha(new \DateTime()); 
        
        $suma = 0; $tot = 0;
        for($i=1; $i<13; $i++){   
        if($editForm["f".$i]->getData()>0){
         $suma = $suma + $editForm["f".$i]->getData();
         $tot = $tot + 1;
        }
        }
       $entity->setF0($suma/$tot);
        
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('docente_coevaltutor'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
         //   'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a coevalTutor entity.
     *
     * @Route("/{id}", name="coevaltutor_delete")
     * @Method("DELETE")
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
            ->getForm()
        ;
    }
}
