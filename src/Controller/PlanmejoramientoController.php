<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Planmejoramiento;
use App\Form\PlanmejoramientoType;

/**
 * Planmejoramiento controller.
 *
 * @Route("/dec/planm")
 */
class PlanmejoramientoController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Lists all Planmejoramiento entities.
     *
     * @Route("/", name="planmejoramiento", methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->doctrine->getManager();
        $session = $request->getSession();
        $escuelaid = $session->get('escuelaid');
        if ($escuelaid == null) {
            return $this->redirect($this->generateUrl('home_user_inicio'));
        }
        $entities = $em->getRepository('App:Planmejoramiento')->findBy(array('autorid' => $escuelaid));

        return $this->render('Planmejoramiento/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Planmejoramiento entity.
     *
     * @Route("/", name="planmejoramiento_create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new Planmejoramiento();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('planmejoramiento_show', array('id' => $entity->getId())));
        }

        return  $this->render('Planmejoramiento/show.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
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
        $form = $this->createForm(PlanmejoramientoType::class, $entity, array(
            'action' => $this->generateUrl('planmejoramiento_create'),
            'method' => 'POST'
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Planmejoramiento entity.
     *
     * @Route("/new", name="planmejoramiento_new", methods={"GET"})
     */
    public function newAction()
    {
        $entity = new Planmejoramiento();
        $form = $this->createCreateForm($entity);

        return $this->render('Planmejoramiento/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }


    /**
     * Seleccionar docente
     * @Route("/add", name="planmejoramiento_add", methods={"GET"})
     */
    public function addAction(Request $request)
    {
        $em = $this->doctrine->getManager();
        $session = $request->getSession();
        $periodoe_id = $session->get('periodoe');
        $periodoe = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $periodoe_id));;
        $escuela = $em->getRepository('App:Escuela')->find($session->get('escuelaid'));
        $entities = $em->getRepository('App:Docente')->findBy(array('escuela' => $escuela,  'periodo' => $periodoe));
        return  $this->render('Planmejoramiento/add.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Agregar Plan Mejoramiento a Docente
     *
     * @Route("/add/{id}", name="planmejoramiento_agregar", methods={"GET"})
     */
    public function agregarAction(Request $request, $id)
    {
        $planm = new Planmejoramiento();
        $em = $this->doctrine->getManager();
        $session = $request->getSession();
        $docente = $em->getRepository('App:Docente')->findOneBy(array('id' => $id));
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
     * @Route("/{id}", name="planmejoramiento_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Planmejoramiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Planmejoramiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Planmejoramiento/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Finds and displays a Planmejoramiento entity.
     *
     */
    public function docAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Planmejoramiento')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Planmejoramiento entity.');
        }

        return $this->render('Planmejoramiento/show.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Planmejoramiento entity.
     *
     * @Route("/{id}/edit", name="planmejoramiento_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Planmejoramiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Planmejoramiento entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('Planmejoramiento/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
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
        $form = $this->createForm(PlanmejoramientoType::class, $entity, array(
            'action' => $this->generateUrl('planmejoramiento_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Planmejoramiento entity.
     *
     * @Route("/{id}", name="planmejoramiento_update", methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Planmejoramiento')->find($id);

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

        return  $this->render('Planmejoramiento/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Planmejoramiento entity.
     *
     * @Route("/{id}", name="planmejoramiento_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:Planmejoramiento')->find($id);

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
            ->add('submit', SubmitType::class, array('label' => 'Borrar Plan'))
            ->getForm();
    }
}
