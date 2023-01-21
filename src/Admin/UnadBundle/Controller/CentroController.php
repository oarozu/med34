<?php

namespace Admin\UnadBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\UnadBundle\Entity\Centro;
use Admin\UnadBundle\Form\CentroType;

/**
 * Centro controller.
 *
 * @Route("/unad/centro")
 */
class CentroController extends Controller
{

    /**
     * Lists all Centro entities.
     *
     * @Route("/", name="centro")
     * @Method("GET")
     * @Template("Centro/index.html.twig")
     */
    public function indexAction()
    {
       $em = $this->getDoctrine()->getManager();
      // $entities = $em->getRepository('AdminUnadBundle:Centro')->findAll();
      $entities = $em->getRepository('AdminUnadBundle:Centro')->ordenZona();
       return array(
       'entities' => $entities,
       );
    }
    /**
     * Creates a new Centro entity.
     *
     * @Route("/", name="centro_create")
     * @Method("POST")
     * @Template("Centro/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Centro();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('centro_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
     * @Route("/new", name="centro_new")
     * @Method("GET")
     * @Template("Centro/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Centro();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Centro entity.
     *
     * @Route("/{id}", name="centro_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Centro entity.
     *
     * @Route("/{id}/edit", name="centro_edit")
     * @Method("GET")
     * @Template("Centro/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
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
     * @Route("/{id}", name="centro_update")
     * @Method("PUT")
     * @Template("Centro/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Centro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Centro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $cedulaDirector =  $editForm["cedulaDirector"]->getData();
        $director = $em->getRepository('AdminUserBundle:User')->find($cedulaDirector);
        $entity->setDirector($director);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('centro_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Centro entity.
     *
     * @Route("/{id}", name="centro_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminUnadBundle:Centro')->find($id);

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
            ->getForm()
        ;
    }

     /**
     * @Route("/docs/{id}/pc", name="centro_docs")
     * @Method("GET")
     * @Template("Centro/docs.html.twig")
     */
    public function docsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $centros = $user->getDirectorcentro();
        $centro = $em->getRepository('AdminUnadBundle:Centro')->findBy(array('id' => $id));
        $docentes = $em->getRepository('AdminUnadBundle:Docente')->findBy(array('centro' => $centro));

        return array(
            'docentes'      => $docentes,
            'centro'    => $centros[0],
            'user'   => $user,
        );
    }


    /**
     * @Route("/docs/index", name="centro_index")
     * @Method("GET")
     * @Template("Centro/lista.html.twig")
     */
    public function listaAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $centros = $user->getDirectorcentro();
        $zonas = $user->getDirectorzona();

        return array(
            'centros'    => $centros,
            'zonas'    => $zonas,
        );
    }


}
