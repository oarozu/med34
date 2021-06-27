<?php

namespace Admin\UnadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\UnadBundle\Entity\Zona;
use Admin\UnadBundle\Form\ZonaType;

/**
 * Zona controller.
 *
 * @Route("/unad/zona")
 */
class ZonaController extends Controller
{

    /**
     * Lists all Zona entities.
     *
     * @Route("/", name="zona")
     * @Method("GET")
     * @Template("Zona/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminUnadBundle:Zona')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Zona entity.
     *
     * @Route("/", name="zona_create")
     * @Method("POST")
     * @Template("Zona/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Zona();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('zona_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Zona entity.
    *
    * @param Zona $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Zona $entity)
    {
        $form = $this->createForm(new ZonaType(), $entity, array(
            'action' => $this->generateUrl('zona_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Zona entity.
     *
     * @Route("/new", name="zona_new")
     * @Method("GET")
     * @Template("Zona/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Zona();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Zona entity.
     *
     * @Route("/{id}", name="zona_show")
     * @Method("GET")
     * @Template("Zona/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Zona')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Zona entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Zona entity.
     *
     * @Route("/{id}/edit", name="zona_edit")
     * @Method("GET")
     * @Template("Zona/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Zona')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Zona entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm["director"]->setData($entity->getDirector()->getId());
        $deleteForm = $this->createDeleteForm($id);
        $editForm["director_nom"]->setData($entity->getDirector()->getNombres().' '.$entity->getDirector()->getApellidos());

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Zona entity.
    *
    * @param Zona $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Zona $entity)
    {
        $form = $this->createForm(new ZonaType(), $entity, array(
            'action' => $this->generateUrl('zona_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        return $form;
    }
    /**
     * Edits an existing Zona entity.
     *
     * @Route("/{id}", name="zona_update")
     * @Method("POST")
     * @Template("Zona/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminUnadBundle:Zona')->find($id);

        if (!$entity) {
            return new JsonResponse(array('message' => 'Unable to find Zona entity'), 500);
           // throw $this->createNotFoundException('Unable to find Zona entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $director = $em->getRepository('AdminUserBundle:User')->find($editForm["director"]->getData());

        if (count($director) == 0){
            return new JsonResponse(
                array(
                    'message' => '<div class="alert alert-warning fade in"><i class="fa-fw fa fa-check"></i><strong>Error !</strong> Director no encontrado.</div>'
                ), 500);

        }

        $entity->setDirector($director);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $editForm = $this->createEditForm($entity);
            $editForm["director"]->setData($entity->getDirector()->getId());
            $editForm["director_nom"]->setData($entity->getDirector()->getNombres().' '.$entity->getDirector()->getApellidos());
            $response = new JsonResponse(
                array(
                    'message' => '<div class="alert alert-success fade in"><i class="fa-fw fa fa-check"></i><strong>Hecho !</strong> Registro actualizado.</div>',
                    'form' => $this->renderView('Zona/form.html.twig',
                        array(
                            'entity' => $entity,
                            'form' => $editForm->createView(),
                        ))), 200);
            return $response;
        }

        $response = new JsonResponse(
            array(
                'message' => 'Error desde Json',
                'form' => $this->renderView('Zona/form.html.twig',
                    array(
                        'entity' => $entity,
                        'form' => $editForm->createView(),
                    ))), 400);
        return $response;
    }


    /**
     * Deletes a Zona entity.
     *
     * @Route("/{id}", name="zona_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminUnadBundle:Zona')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Zona entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('zona'));
    }

    /**
     * Creates a form to delete a Zona entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('zona_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    
    
        /**
     * @Route("/docs/{id}", name="zona_index")
     * @Method("GET")
     * @Template("Zona/docs.html.twig")
     */
    public function listaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $zona = $user->getDirectorzona();
        $periodos = $em->getRepository('AdminMedBundle:Periodoe')->findAll();
        if ($id == 1){
            $id = $this->container->getParameter('appmed.periodo');
        }
        $centros = $em->getRepository('AdminUnadBundle:Centro')->findBy(array('zona' => $zona[0]));
        $docentes = $em->getRepository('AdminUnadBundle:Docente')->findBy(array('centro' => $centros, 'periodo' => $id));
        
        return array(
            'docentes'      => $docentes,
            'zona'        => $zona[0],
            'periodos'    => $periodos,
            'periodo'     => $id,
        );
    }
}
