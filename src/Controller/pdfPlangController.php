<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\pdfPlang;
use App\Form\pdfPlangType;
use App\Service\FileUploader;

/**
 * pdfPlang controller.
 *
 * @Route("/dec/pdfplang")
 */
class pdfPlangController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Lists all formatoPlang entities.
     *
     * @Route("/", name="pdfplang", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->doctrine->getManager();

        $entities = $em->getRepository('App:formatoPlang')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new formatoPlang entity.
     *
     * @Route("/crear/{id}", name="pdfplang_create", methods={"POST"})
     */
    public function createAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $docente = $em->getRepository('App:Docente')->find($id);
        $plan = $em->getRepository('App:Plangestion')->findOneBy(array('docente' => $docente));
        $entity = new pdfPlang($id,$docente->getPeriodo());
        $plan->setPdf($entity);
        //$entity->setPlan($plan);
        $form = $this->createCreateForm($entity, $id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('avalplang'));
        }

        return $this->render('pdfPlang/new.html.twig', array(
            'entity' => $entity,
            'id'      => $id,
            'form'   => $form->createView(),
        ));
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
     */
    public function newAction($id)
    {

        $em = $this->doctrine->getManager();
        $docente = $em->getRepository('App:Docente')->find($id);
        $entity = new pdfPlang($id,$docente->getPeriodo());

        $form   = $this->createCreateForm($entity,$id);

        $pathproyect =  dirname(__DIR__).'/../web';

        return $this->render('pdfPlang/new.html.twig', array(
            'entity' => $entity,
            'id'    => $id,
            'form'   => $form->createView(),
            'pathp' => $pathproyect
        ));
    }


    /**
     * Displays a form to edit an existing formatoPlang entity.
     *
     * @Route("/{id}/edit", name="pdfplang_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:formatoPlang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find formatoPlang entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('formatoPlang/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:formatoPlang')->find($id);

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

        return $this->render('formatoPlang/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:formatoPlang')->find($id);

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
