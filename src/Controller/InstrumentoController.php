<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Instrumento;
use App\Form\InstrumentoType;

/**
 * Instrumento controller.
 *
 * @Route("/admin/instrumento")
 */
class InstrumentoController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Lists all Instrumento entities.
     *
     * @Route("/", name="admin_instrumento", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->doctrine->getManager();

        $entities = $em->getRepository('App:Instrumento')->findAll();

        return $this->render('Instrumento/index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Instrumento entity.
     *
     * @Route("/", name="admin_instrumento_create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new Instrumento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('instrumento_show', array('id' => $entity->getId())));
        }

        return $this->render('Instrumento/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Instrumento entity.
    *
    * @param Instrumento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Instrumento $entity)
    {
        $form = $this->createForm(InstrumentoType::class, $entity, array(
            'action' => $this->generateUrl('instrumento_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Instrumento entity.
     *
     * @Route("/new", name="admin_instrumento_new", methods={"GET"})
     */
    public function newAction()
    {
        $entity = new Instrumento();
        $form   = $this->createCreateForm($entity);

        return $this->render('Instrumento/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Instrumento entity.
     *
     * @Route("/{id}", name="admin_instrumento_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Instrumento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Instrumento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Instrumento/show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Instrumento entity.
     *
     * @Route("/{id}/edit", name="admin_instrumento_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Instrumento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Instrumento entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Instrumento/edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Instrumento entity.
    *
    * @param Instrumento $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Instrumento $entity)
    {
        $form = $this->createForm(InstrumentoType::class, $entity, array(
            'action' => $this->generateUrl('admin_instrumento_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));
        return $form;
    }
 /**
     * Edits an existing Instrumento entity.
     *
     * @Route("/{id}", name="admin_instrumento_update", methods={"POST"})
     */
    public function updateAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
        return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Instrumento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Instrumento entity.');
        }
        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
        $em->persist($entity);
        $em->flush();
        $editForm = $this->createEditForm($entity);
        $response = new JsonResponse(
        array(
            'message' => '<div class="alert alert-success fade in"><i class="fa-fw fa fa-check"></i><strong>Hecho !</strong> Registro actualizado.</div>',
            'form' => $this->renderView('App:Instrumento:form.html.twig',
             array(
            'entity' => $entity,
            'form' => $editForm->createView(),
             ))), 200);
            return $response;
        }

        $response = new JsonResponse(
        array(
            'message' => 'Error desde Json',
            'form' => $this->renderView('App:Instrumento:form.html.twig',
             array(
            'entity' => $entity,
            'form' => $editForm->createView(),
             ))), 400);
            return $response;
    }

    /**
     * Deletes a Instrumento entity.
     *
     * @Route("/{id}", name="admin_instrumento_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:Instrumento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Instrumento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_instrumento'));
    }

    /**
     * Creates a form to delete a Instrumento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_instrumento_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Borrar'))
            ->getForm()
        ;
    }
}
