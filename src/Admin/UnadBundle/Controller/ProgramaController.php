<?php

namespace Admin\UnadBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\UnadBundle\Entity\Programa;
use Admin\UnadBundle\Form\ProgramaType;

/**
 * Programa controller.
 *
 * @Route("/unad/programa")
 */
class ProgramaController extends Controller
{

    /**
     * Lists all Programa entities.
     *
     * @Route("/", name="programa")
     * @Method("GET")
     * @Template("Programa/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_SECA')) {

            $entities = $em->getRepository('AdminUnadBundle:Programa')->getPorEscuela($session->get('escuelaid'));
        } else {
            $periodo = $em->getRepository('AdminMedBundle:Periodoe')->findBy(array('id' => $session->get('periodoe')));
            $entities = $em->getRepository('AdminUnadBundle:Programa')->findAll();
        }
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all Programa entities.
     *
     * @Route("/pe/{id}", name="programa_periodo")
     * @Method("GET")
     * @Template("Programa/porperiodo.html.twig")
     */
    public function porperiodoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminUnadBundle:ProgramaPeriodo')->findBy(array('periodo' => $id));

        return array(
            'entities' => $entities,
        );
    }


    /**
     * Creates a new Programa entity.
     *
     * @Route("/", name="programa_create")
     * @Method("POST")
     * @Template("AdminUnadBundle:Programa:new.html.twig")
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

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
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
     * @Route("/new", name="programa_new")
     * @Method("GET")
     * @Template("Programa/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Programa();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Programa entity.
     *
     * @Route("/{id}", name="programa_show")
     * @Method("GET")
     * @Template("Programa/show.html.twig")
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $programa = $em->getRepository('AdminUnadBundle:Programa')->find($id);
        $cursos = $em->getRepository('AdminUnadBundle:Curso')->findBy(array('programa' => $id));
        $periodoa = $em->getRepository('AdminMedBundle:Periodoa')->findBy(array('periodoe' => $session->get('periodoe')));
        $oferta = $em->getRepository('AdminMedBundle:Oferta')->findBy(array('curso' => $cursos, 'periodo' => $periodoa));

        if (!$programa) {
            throw $this->createNotFoundException('Unable to find Programa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $programa,
            'oferta' => $oferta,
            'periodo' => $session->get('periodoe'),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Finds and displays a Programa entity.
     * @Route("/{id}/modal", name="programa_modal")
     * @Method("GET")
     * @Template("Programa/modal.html.twig")
     */
    public function modalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminUnadBundle:ProgramaPeriodo')->find($id);
        $cursos = $em->getRepository('AdminUnadBundle:Curso')->findBy(array('programa' => $entity->getPrograma()));
        $periodoa = $em->getRepository('AdminMedBundle:Periodoa')->findBy(array('periodoe' => $entity->getPeriodo()->getId()));
        $oferta = $em->getRepository('AdminMedBundle:Oferta')->findBy(array('curso' => $cursos, 'periodo' => $periodoa));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Programa entity.');
        }
        return array(
            'entity' => $entity,
            'oferta' => $oferta
        );
    }


    /**
     * Finds and displays a Programa entity.
     * @Route("/{id}/cursos", name="programa_cursos")
     * @Method("GET")
     * @Template("Programa/modal.html.twig")
     */
    public function programacursos($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminUnadBundle:ProgramaPeriodo')->find($id);
        $cursos = $em->getRepository('AdminUnadBundle:Curso')->findBy(array('programa' => $entity->getPrograma()));
        $periodoa = $em->getRepository('AdminMedBundle:Periodoa')->findBy(array('periodoe' => $entity->getPeriodo()->getId()));
        $oferta = $em->getRepository('AdminMedBundle:Oferta')->findBy(array('curso' => $cursos, 'periodo' => $periodoa));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Programa entity.');
        }
        return array(
            'entity' => $entity,
            'oferta' => $oferta
        );
    }

    /**
     * Displays a form to edit an existing Programa entity.
     *
     * @Route("/{id}/edit", name="programa_edit")
     * @Method("GET")
     * @Template("Programa/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Programa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Programa entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
     * @Route("/{id}", name="programa_update")
     * @Method("PUT")
     * @Template("Programa/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AdminUnadBundle:Programa')->find($id);
        $periodo = $em->getRepository('AdminMedBundle:Periodoe')->find($session->get('periodoe'));
        $programa = $em->getRepository('AdminUnadBundle:ProgramaPeriodo')->findOneBy(array('programa' => $entity, 'periodo' => $periodo));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Programa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm["lider"]->getData() != null) {
            $lider = $em->getRepository('AdminUnadBundle:Docente')->find($editForm["lider"]->getData());
            $entity->setLider($lider);
            $programa->setLider($lider);
        }
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('escuela_info'));

        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Programa entity.
     *
     * @Route("/{id}", name="programa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminUnadBundle:Programa')->find($id);

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
     * @Route("/add/lider", name="programa_addlider")
     * @Method("GET")
     * @Template("Programa/addlider.html.twig")
     */
    public function addliderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $periodo = $em->getRepository('AdminMedBundle:Periodoe')->find($session->get('periodoe'));
        $escuela = $em->getRepository('AdminUnadBundle:Escuela')->find($session->get('escuelaid'));
        $entities = $em->getRepository('AdminUnadBundle:Docente')->findBy(array('escuela' => $escuela, 'periodo' => $session->get('periodoe')));
        return array(
            'entities' => $entities,
            'periodo' => $periodo
        );
    }
}
