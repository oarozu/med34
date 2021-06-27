<?php
namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\Accionespm;
use Admin\MedBundle\Form\AccionespmType;
use Admin\MedBundle\Form\AccionespmdocType;

/**
 * Accionespm controller.
 *
 * @Route("/dec/planm/acciones")
 */
class AccionespmController extends Controller
{

    /**
     * Lists all Accionespm entities.
     *
     * @Route("/", name="accionespm")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMedBundle:Accionespm')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Accionespm entity.
     *
     * @Route("/", name="accionespm_create")
     * @Method("POST")
     * @Template("AdminMedBundle:Accionespm:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Accionespm();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('planmejoramiento_show', array('id' => $entity->getPlan()->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Accionespm entity.
    * @param Accionespm $entity The entity
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Accionespm $entity)
    {
        $form = $this->createForm(new AccionespmType(), $entity, array(
            'action' => $this->generateUrl('accionespm_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Agregar'));
        return $form;
    }
    
    
    /**
    * Creates a form to create a Accionespm entity.
    * @param Accionespm $entity The entity
    * @return \Symfony\Component\Form\Form The form
    */
    private function crearDocenteForm(Accionespm $entity)
    {
        $form = $this->createForm(new AccionespmdocType(), $entity, array(
            'action' => $this->generateUrl('accionespm_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Agregar'));
        return $form;
    }

    /**
     * Displays a form to create a new Accionespm entity.
     *
     * @Route("/new/{id}", name="accionespm_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new Accionespm();
        $em = $this->getDoctrine()->getManager();
        $plan = $em->getRepository('AdminMedBundle:Planmejoramiento')->findOneBy(array('id' => $id));
        $entity->setPlan($plan);
        $fecha = new \DateTime();
        $fecha->modify('+1 month');
        $entity->setFechaProyectada($fecha);
        $form   = $this->createCreateForm($entity);
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id'    => $id
        );
    }

    /**
     * Finds and displays a Accionespm entity.
     *
     * @Route("/{id}", name="accionespm_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Accionespm')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Accionespm entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Accionespm entity.
     *
     * @Route("/{id}/edit", name="accionespm_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Accionespm')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Accionespm entity.');
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
     * Displays a form to edit an existing Accionespm entity.
     * @Route("/{id}/editar", name="accionespm_editar")
     * @Template()
     */
    public function editarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminMedBundle:Accionespm')->find($id);
        //$defaultData = array('id' => $id);
        $idplan = $entity->getPlan()->getId();
        $form = $this->createFormBuilder($entity)
        ->add('estado', ChoiceType::class, array(
        'placeholder' => 'Cumplio?',
        'label' => 'Tipo ',
        'attr' => array('class' => 'input-lg'),   
        'choices'   => array(
        '1' => 'SI', 
        '0' => 'NO'
            ),
        'required'  => true,))      
        ->add('submit', SubmitType::class, array('label' => 'Actualizar'))
        ->getForm();
         if ($request->isMethod('POST')) {
            $form->bind($request);
            $entity->setEstado($form->get('estado')->getData());
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('planmejoramiento_show', array('id' => $idplan)));
        }        
        return array(
            'entity'      => $entity,
            'edit_form'   => $form->createView(),
        );
    } 
    
      /**
     * Docente Actualiza Accion
     * @Template()
     */
    public function editdocAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminMedBundle:Accionespm')->find($id);

        $idplan = $entity->getPlan()->getId();
        $form = $this->crearDocenteForm($entity);
        
         if ($request->isMethod('POST')) {
            $form->bind($request);
            //$data = $form->getData();
            $entity->setObservaciones($form->get('observaciones')->getData());
            
            $fecha = new \DateTime();
            $entity->setFechaCierre($fecha);
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('planmejoramiento_doc', array('id' => $idplan)));
        }        
        return array(
            'entity'      => $entity,
            'edit_form'   => $form->createView(),
        );
    } 
    
    /**
    * Creates a form to edit a Accionespm entity.
    *
    * @param Accionespm $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Accionespm $entity)
    {
        $form = $this->createForm(new AccionespmType(), $entity, array(
            'action' => $this->generateUrl('accionespm_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Accionespm entity.
     *
     * @Route("/{id}", name="accionespm_update")
     * @Method("PUT")
     * @Template("AdminMedBundle:Accionespm:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Accionespm')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Accionespm entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

        return $this->redirect($this->generateUrl('planmejoramiento_show', array('id' => $entity->getPlan()->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Accionespm entity.
     *
     * @Route("/{id}", name="accionespm_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:Accionespm')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Accionespm entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('planmejoramiento_show', array('id' => $entity->getPlan()->getId())));
    }

    /**
     * Creates a form to delete a Accionespm entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('accionespm_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Borrar item'))
            ->getForm()
        ;
    }
}
