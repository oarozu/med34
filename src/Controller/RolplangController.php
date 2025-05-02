<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Rolplang;
use App\Form\RolplangType;

/**
 * Rolplang controller.
 *
 * @Route("/doc/rolplang")
 */
class RolplangController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Creates a new Rolplang entity.
     *
     * @Route("/{id}", name="rolplang_create", methods={"POST"})
     */
    public function createAction(Request $request, $id)
    {
        $entity = new Rolplang();

        $em = $this->doctrine->getManager();
        $plang = $em->getRepository('App:Plangestion')->find($id);
        $entity->setPlang($plang);
        $form = $this->createCreateForm($entity, $id, $plang->getDias());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();

            try {
                $em->persist($entity);
                $em->flush();
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->get('session')->getFlashBag()->add('error', 'No puede agregar un rol dos veces');
            }

            return $this->redirect($this->generateUrl('plangestion_crear', array('id' => $id)));
        }
        $libre = 0;
        // foreach ($roles as $rolok){
        //$libre = $libre + $rolok->getHoras();
        // }
        return $this->render('Rolplang/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'id' => $id,
            'libre' => $libre,
        ));
    }

    /**
     * Creates a form to create a Rolplang entity.
     *
     * @param Rolplang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Rolplang $entity, $id, $dias)
    {
        $form = $this->createForm(RolplangType::class, $entity, array(
                'action' => $this->generateUrl('rolplang_create', array('id' => $id)),
                'method' => 'POST',
                'dias' => $dias
            )
        );

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Rolplang entity.
     *
     * @Route("/new/{id}/{idr}", name="rolplang_new", methods={"GET"})
     */
    public function newAction($id, $idr)
    {
        $entity = new Rolplang();
        $em = $this->doctrine->getManager();
        $plang = $em->getRepository('App:Plangestion')->find($id);
        $rol = $em->getRepository('App:Rolacademico')->find($idr);
        $roles = $em->getRepository('App:Rolplang')->findBy(array('plang' => $plang));
        $libre = 0;
        foreach ($roles as $rolok) {
            $libre = $libre + $rolok->getHoras();
        }
        $entity->setRol($rol);
        $entity->setPlang($plang);
        $form = $this->createCreateForm($entity, $id, $plang->getDias());

        return $this->render('Rolplang/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'id' => $id,
            'libre' => $libre,
            'total' => $plang->getDias() * 8,
        ));
    }

    /**
     * Finds and displays a Rolplang entity.
     *
     * @Route("/{id}", name="rolplang_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Rolplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rolplang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Rolplang/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Rolplang entity.
     *
     * @Route("/{id}/edit", name="rolplang_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Rolplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rolplang entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Rolplang/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Rolplang entity.
     *
     * @param Rolplang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Rolplang $entity)
    {
        $form = $this->createForm(RolplangType::class, $entity, array(
            'action' => $this->generateUrl('rolplang_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Rolplang entity.
     *
     * @Route("/{id}", name="rolplang_update", methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Rolplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rolplang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('rolplang_edit', array('id' => $id)));
        }

        return $this->render('Rolplang/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Rolplang entity.
     *
     * @Route("/{id}", name="rolplang_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:Rolplang')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rolplang entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('plangestion_crear', array('id' => $entity->getPlang()->getId())));
    }

    /**
     * Creates a form to delete a Rolplang entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rolplang_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Borrar', 'attr' => array('class' => 'btn btn-labeled btn-success')))
            ->getForm();
    }
}
