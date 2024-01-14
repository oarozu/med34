<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\pdfPlang;
use Admin\MedBundle\Form\pdfPlangType;

/**
 * pdfPlang controller.
 *
 * @Route("/dec/pdfplang")
 */
class pdfPlangController extends Controller
{

    /**
     * Lists all formatoPlang entities.
     *
     * @Route("/", name="pdfplang", methods={"GET"})
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMedBundle:formatoPlang')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new formatoPlang entity.
     *
     * @Route("/crear/{id}", name="pdfplang_create", methods={"POST"})
     * @Template("AdminMedBundle:pdfPlang:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($id);
        $plan = $em->getRepository('AdminMedBundle:Plangestion')->findOneBy(array('docente' => $docente));
        $entity = new pdfPlang($id,$docente->getPeriodo());
        $plan->setPdf($entity);
        //$entity->setPlan($plan);
        $form = $this->createCreateForm($entity, $id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('avalplang'));
        }

        return array(
            'entity' => $entity,
            'id'      => $id,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a formatoPlang entity.
    *
    * @param pdfPlang $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(pdfPlang $entity, $id)
    {
        $form = $this->createForm(pdfPlangType::class, $entity, array(
            'action' => $this->generateUrl('pdfplang_create', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Cargar'));

        return $form;
    }

    /**
     * Displays a form to create a new pdfPlang entity.
     *
     * @Route("/new/{id}", name="pdfplang_new", methods={"GET"})
     * @Template("AdminMedBundle:formatoPlang:new.html.twig")
     */
    public function newAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $docente = $em->getRepository('AdminUnadBundle:Docente')->find($id);
        $entity = new pdfPlang($id,$docente->getPeriodo());

        $form   = $this->createCreateForm($entity,$id);

        return array(
            'entity' => $entity,
            'id'    => $id,
            'form'   => $form->createView(),
        );
    }


    /**
     * Displays a form to edit an existing formatoPlang entity.
     *
     * @Route("/{id}/edit", name="pdfplang_edit", methods={"GET"})
     * @Template("AdminMedBundle:formatoPlang:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:formatoPlang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find formatoPlang entity.');
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
    * Creates a form to edit a formatoPlang entity.
    *
    * @param formatoPlang $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(formatoPlang $entity)
    {
        $form = $this->createForm(formatoPlangType::class, $entity, array(
            'action' => $this->generateUrl('formatoplang_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing formatoPlang entity.
     *
     * @Route("/{id}", name="formatoplang_update", methods={"PUT"})
     * @Template("AdminMedBundle:formatoPlang:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:formatoPlang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find formatoPlang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('formatoplang_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a formatoPlang entity.
     *
     * @Route("/{id}", name="formatoplang_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:formatoPlang')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find formatoPlang entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('formatoplang'));
    }

    /**
     * Creates a form to delete a formatoPlang entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('formatoplang_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
