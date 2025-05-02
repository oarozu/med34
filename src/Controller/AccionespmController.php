<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Accionespm;
use App\Form\AccionespmType;
use App\Form\AccionespmdocType;
use Doctrine\Persistence\ManagerRegistry;


/**
 * Accionespm controller.
 *
 * @Route("/dec/planm/acciones")
 */
class AccionespmController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Lists all Accionespm entities.
     *
     * @Route("/", name="accionespm", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->doctrine->getManager();

        $entities = $em->getRepository('App:Accionespm')->findAll();

        return $this->render('Accionespm/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Accionespm entity.
     *
     * @Route("/", name="accionespm_create",  methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new Accionespm();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('planmejoramiento_show', array('id' => $entity->getPlan()->getId())));
        }

        return $this->render('Accionespm/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Accionespm entity.
    * @param Accionespm $entity The entity
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Accionespm $entity)
    {
        $form = $this->createForm(AccionespmType::class, $entity, array(
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
        $form = $this->createForm(AccionespmdocType::class, $entity, array(
            'action' => $this->generateUrl('accionespm_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Agregar'));
        return $form;
    }

    /**
     * Displays a form to create a new Accionespm entity.
     *
     * @Route("/new/{id}", name="accionespm_new",  methods={"GET"})
     */
    public function newAction($id)
    {
        $entity = new Accionespm();
        $em = $this->doctrine->getManager();
        $plan = $em->getRepository('App:Planmejoramiento')->findOneBy(array('id' => $id));
        $entity->setPlan($plan);
        $fecha = new \DateTime();
        $fecha->modify('+1 month');
        $entity->setFechaProyectada($fecha);
        $form   = $this->createCreateForm($entity);
        return $this->render('Accionespm/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id'    => $id
        ));
    }

    /**
     * Finds and displays a Accionespm entity.
     *
     * @Route("/{id}", name="accionespm_show",  methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Accionespm')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Accionespm entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Accionespm/show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Accionespm entity.
     *
     * @Route("/{id}/edit", name="accionespm_edit",  methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Accionespm')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Accionespm entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Accionespm/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

       /**
     * Displays a form to edit an existing Accionespm entity.
     * @Route("/{id}/editar", name="accionespm_editar",  methods={"GET"})
     */
    public function editarAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Accionespm')->find($id);
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
        return $this->render('Accionespm/editar.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $form->createView(),
        ));
    }

      /**
     * Docente Actualiza Accion
     */
    public function editdocAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Accionespm')->find($id);

        $idplan = $entity->getPlan()->getId();
        $form = $this->crearDocenteForm($entity);

         if ($request->isMethod('POST')) {
            $form->bind($request);
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
        $form = $this->createForm(AccionespmType::class, $entity, array(
            'action' => $this->generateUrl('accionespm_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Accionespm entity.
     *
     * @Route("/{id}", name="accionespm_update",  methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Accionespm')->find($id);

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

        return $this->render('Accionespm/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Accionespm entity.
     *
     * @Route("/{id}", name="accionespm_delete",  methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:Accionespm')->find($id);

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
