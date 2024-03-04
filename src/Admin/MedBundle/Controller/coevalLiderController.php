<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\coevalLider;
use Admin\MedBundle\Form\coevalLiderType;

/**
 * coevalLider controller.
 *
 * @Route("/dec/coevallider")
 */
class coevalLiderController extends Controller
{

    /**
     * Creates a new coevalLider entity.
     *
     * @Route("/", name="coevallider_create", methods={"POST"})
     * @Template("coevalLider/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new coevalLider();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('coevallider_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a coevalLider entity.
    *
    * @param coevalLider $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(coevalLider $entity)
    {
        $form = $this->createForm(coevalLiderType::class, $entity, array(
            'action' => $this->generateUrl('coevallider_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }


    /**
     * Finds and displays a coevalLider entity.
     *
     * @Route("/{id}", name="coevallider_show", methods={"GET"})
     * @Template("coevalLider/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:coevalLider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find coevalLider entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing coevalLider entity.
     *
     * @Route("/{id}/edit", name="coevallider_edit", methods={"GET"})
     * @Template("coevalLider/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:coevalLider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find coevalLider entity.');
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
    * Creates a form to edit a coevalLider entity.
    *
    * @param coevalLider $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(coevalLider $entity)
    {
        $form = $this->createForm(coevalLiderType::class, $entity, array(
            'action' => $this->generateUrl('coevallider_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing coevalLider entity.
     *
     * @Route("/{id}", name="coevallider_update", methods={"PUT"})
     * @Template("coevalLider/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:coevalLider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find coevalLider entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $entity->setFecha(new \DateTime());
        $suma = 0; $tot = 0;
        for($i=1; $i<15; $i++){
        if($editForm["f".$i]->getData()>0){
         $suma = $suma + $editForm["f".$i]->getData();
         $tot = $tot + 1;
        }
        }
       $entity->setF0($suma/$tot);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('decano_coevallider'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a coevalLider entity.
     *
     * @Route("/{id}", name="coevallider_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:coevalLider')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find coevalLider entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('coevallider'));
    }

    /**
     * Creates a form to delete a coevalLider entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('coevallider_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
