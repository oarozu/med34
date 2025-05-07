<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Centro;
use App\Form\CentroType;

/**
 * Centro controller.
 *
 * @Route("/unad/centro")
 */
class CentroController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Lists all Centro entities.
     *
     * @Route("/", name="centro", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->doctrine->getManager();
        $entities = $em->getRepository('App:Centro')->ordenZona();
        return $this->render('Centro/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Centro entity.
     *
     * @Route("/", name="centro_create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new Centro();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('centro_show', array('id' => $entity->getId())));
        }

        return $this->render('Centro/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Centro entity.
     *
     * @param Centro $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Centro $entity)
    {
        $form = $this->createForm(CentroType::class, $entity, array(
            'action' => $this->generateUrl('centro_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Centro entity.
     *
     * @Route("/new", name="centro_new", methods={"GET"})
     */
    public function newAction()
    {
        $entity = new Centro();
        $form = $this->createCreateForm($entity);

        return $this->render('Centro/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Centro entity.
     *
     * @Route("/{id}", name="centro_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        return $this->render('Centro/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Centro entity.
     *
     * @Route("/{id}/edit", name="centro_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Centro/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Centro entity.
     *
     * @param Centro $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Centro $entity)
    {
        $form = $this->createForm(CentroType::class, $entity, array(
            'action' => $this->generateUrl('centro_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Actualizar'));

        return $form;
    }

    /**
     * Edits an existing Centro entity.
     *
     * @Route("/{id}", name="centro_update", methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $cedulaDirector = $editForm["cedulaDirector"]->getData();
        $director = $em->getRepository('App:User')->find($cedulaDirector);
        $entity->setDirector($director);

        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('centro_edit', array('id' => $id)));
        }

        return $this->render('Centro/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Centro entity.
     *
     * @Route("/{id}", name="centro_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:Centro')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Centro entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('centro'));
    }

    /**
     * Creates a form to delete a Centro entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('centro_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * @Route("/docs/{id}/pc", name="centro_docs", methods={"GET"})
     */
    public function docsAction($id)
    {
        $em = $this->doctrine->getManager();
        $user = $this->getUser();
        $centros = $user->getDirectorcentro();
        $centro = $em->getRepository('App:Centro')->findBy(array('id' => $id));
        $docentes = $em->getRepository('App:Docente')->findBy(array('centro' => $centro));

        return $this->render('Centro/docs.html.twig', array(
            'docentes' => $docentes,
            'centro' => $centros[0],
            'user' => $user,
        ));
    }


    /**
     * @Route("/docs/index", name="centro_index", methods={"GET"})
     */
    public function listaAction()
    {
        $user = $this->getUser();
        $centros = $user->getDirectorcentro();
        $zonas = $user->getDirectorzona();

        return $this->render('Centro/lista.html.twig', array(
            'centros' => $centros,
            'zonas' => $zonas,
        ));
    }
}
