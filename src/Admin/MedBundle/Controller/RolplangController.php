<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\Rolplang;
use Admin\MedBundle\Form\RolplangType;

/**
 * Rolplang controller.
 *
 * @Route("/doc/rolplang")
 */
class RolplangController extends Controller
{

    /**
     * Lists all Rolplang entities.
     *
     * @Route("/", name="rolplang")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMedBundle:Rolplang')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Rolplang entity.
     *
     * @Route("/{id}", name="rolplang_create")
     * @Method("POST")
     * @Template("AdminMedBundle:Rolplang:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $entity = new Rolplang();
        
        $em = $this->getDoctrine()->getManager();
        $plang = $em->getRepository('AdminMedBundle:Plangestion')->find($id);
        $entity->setPlang($plang);
        $form = $this->createCreateForm($entity, $id, $plang->getDias());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

           try{
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
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id'    => $id,
            'libre' => $libre,
        );
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
        $form = $this->createForm(RolplangType::class($dias), $entity, array(
            'action' => $this->generateUrl('rolplang_create', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Rolplang entity.
     *
     * @Route("/new/{id}/{idr}", name="rolplang_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id,$idr)
    {
        $entity = new Rolplang();
        $em = $this->getDoctrine()->getManager();
        $plang = $em->getRepository('AdminMedBundle:Plangestion')->find($id);
        $rol = $em->getRepository('AdminMedBundle:Rolacademico')->find($idr);
        $roles = $em->getRepository('AdminMedBundle:Rolplang')->findBy(array('plang' => $plang));
        $libre = 0;
        foreach ($roles as $rolok){ 
        $libre = $libre + $rolok->getHoras();   
        }
        $entity->setRol($rol);
        $entity->setPlang($plang);
        $form   = $this->createCreateForm($entity, $id,$plang->getDias());

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id'     => $id,
            'libre'   => $libre,
            'total'  => $plang->getDias()*8,
        );
    }

    /**
     * Finds and displays a Rolplang entity.
     *
     * @Route("/{id}", name="rolplang_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Rolplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rolplang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Rolplang entity.
     *
     * @Route("/{id}/edit", name="rolplang_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Rolplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rolplang entity.');
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
     * @Route("/{id}", name="rolplang_update")
     * @Method("PUT")
     * @Template("AdminMedBundle:Rolplang:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Rolplang')->find($id);

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

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Rolplang entity.
     *
     * @Route("/{id}", name="rolplang_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:Rolplang')->find($id);

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
            ->getForm()
        ;
    }
}
