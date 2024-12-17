<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Programa;
use App\Form\ProgramaType;

/**
 * Programa controller.
 *
 * @Route("/unad/programa")
 */
class ProgramaController extends AbstractController
{
    /**
     * Lists all Programa entities.
     *
     * @Route("/", name="programa", methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_SECA')) {

            $entities = $em->getRepository('App:Programa')->getPorEscuela($session->get('escuelaid'));
        } else {
            $periodo = $em->getRepository('App:Periodoe')->findBy(array('id' => $session->get('periodoe')));
            $entities = $em->getRepository('App:Programa')->findAll();
        }
        return $this->render('Programa/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Lists all Programa entities.
     *
     * @Route("/pe/{id}", name="programa_periodo", methods={"GET"})
     */
    public function porperiodoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('App:ProgramaPeriodo')->findBy(array('periodo' => $id));
        return $this->render('Programa/porperiodo.html.twig', array(
            'entities' => $entities,
        ));
    }


    /**
     * Creates a new Programa entity.
     *
     * @Route("/", name="programa_create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new Programa();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('programa_show', array('id' => $entity->getId())));
        }

        return $this->render('Programa/new.html.twig',array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Programa entity.
     *
     * @param Programa $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Programa $entity)
    {
        $form = $this->createForm(ProgramaType::class, $entity, array(
            'action' => $this->generateUrl('programa_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Programa entity.
     *
     * @Route("/new", name="programa_new", methods={"GET"})
     */
    public function newAction()
    {
        $entity = new Programa();
        $form = $this->createCreateForm($entity);

        return $this->render('Programa/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Programa entity.
     *
     * @Route("/{id}", name="programa_show", methods={"GET"})
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $programa = $em->getRepository('App:Programa')->find($id);
        $cursos = $em->getRepository('App:Curso')->findBy(array('programa' => $id));
        $periodoa = $em->getRepository('App:Periodoa')->findBy(array('periodoe' => $session->get('periodoe')));
        $oferta = $em->getRepository('App:Oferta')->findBy(array('curso' => $cursos, 'periodo' => $periodoa));

        if (!$programa) {
            throw $this->createNotFoundException('Unable to find Programa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Programa/show.html.twig', array(
            'entity' => $programa,
            'oferta' => $oferta,
            'periodo' => $session->get('periodoe'),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Programa entity.
     * @Route("/{id}/modal", name="programa_modal", methods={"GET"})
     */
    public function modalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:ProgramaPeriodo')->find($id);
        $cursos = $em->getRepository('App:Curso')->findBy(array('programa' => $entity->getPrograma()));
        $periodoa = $em->getRepository('App:Periodoa')->findBy(array('periodoe' => $entity->getPeriodo()->getId()));
        $oferta = $em->getRepository('App:Oferta')->findBy(array('curso' => $cursos, 'periodo' => $periodoa));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Programa entity.');
        }
        return  $this->render('Programa/modal.html.twig', array(
            'entity' => $entity,
            'oferta' => $oferta
        ));
    }


    /**
     * Finds and displays a Programa entity.
     * @Route("/{id}/cursos", name="programa_cursos", methods={"GET"})
     */
    public function programacursos($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:ProgramaPeriodo')->find($id);
        $cursos = $em->getRepository('App:Curso')->findBy(array('programa' => $entity->getPrograma()));
        $periodoa = $em->getRepository('App:Periodoa')->findBy(array('periodoe' => $entity->getPeriodo()->getId()));
        $oferta = $em->getRepository('App:Oferta')->findBy(array('curso' => $cursos, 'periodo' => $periodoa));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Programa entity.');
        }
        return $this->render('Programa/modal.html.twig', array(
            'entity' => $entity,
            'oferta' => $oferta
        ));
    }

    /**
     * Displays a form to edit an existing Programa entity.
     *
     * @Route("/{id}/edit", name="programa_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App:Programa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Programa entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Programa/edit.html.twig',  array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Programa entity.
     *
     * @param Programa $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Programa $entity)
    {
        $form = $this->createForm(ProgramaType::class, $entity, array(
            'action' => $this->generateUrl('programa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Programa entity.
     *
     * @Route("/{id}", name="programa_update", methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('App:Programa')->find($id);
        $periodo = $em->getRepository('App:Periodoe')->find($session->get('periodoe'));
        $programa = $em->getRepository('App:ProgramaPeriodo')->findOneBy(array('programa' => $entity, 'periodo' => $periodo));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Programa entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm["lider"]->getData() != null) {
            $lider = $em->getRepository('App:Docente')->find($editForm["lider"]->getData());
            $entity->setLider($lider->getUser());
            $programa->setLider($lider);
        }
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('escuela_info'));

        }

        return $this->render('Programa/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Programa entity.
     *
     * @Route("/{id}", name="programa_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('App:Programa')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Programa entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('programa'));
    }

    /**
     * Creates a form to delete a Programa entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('programa_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }


    /**
     * Seleccionar docente
     * @Route("/add/lider", name="programa_addlider", methods={"GET"})
     */
    public function addliderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $periodo = $em->getRepository('App:Periodoe')->find($session->get('periodoe'));
        $escuela = $em->getRepository('App:Escuela')->find($session->get('escuelaid'));

        $entities = $em->getRepository('App:Docente')->findby(array('escuela' => $escuela, 'vinculacion' => ['DO', 'DC'], 'modalidad' => ['TC'], 'periodo' => $session->get('periodoe')), array('id' => 'DESC'), 800);
        return $this->render('Programa/addlider.html.twig', array(
            'entities' => $entities,
            'periodo' => $periodo
        ));
    }
}
