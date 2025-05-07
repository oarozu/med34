<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\coevalDirector;
use App\Entity\Programa;
use App\Form\coevalDirectorType;

/**
 * coevalDirector controller.
 *
 * @Route("/doc/coevaldirector")
 */
class coevalDirectorController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Lists all coevalDirector entities.
     *
     * @Route("/home", name="docente_coevaldirector",  methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->doctrine->getManager();
        $session = $request->getSession();
        $session->set('periodoe', $this->getParameter('appmed.periodo'));
        $id = $this->getParameter('appmed.periodo');
        $user = $this->getUser();
        $programas = $em->getRepository('App:Programa')->findBy(array('lider' => $user));
        $cursos = $em->getRepository('App:Curso')->findBy(array('programa' => $programas));
        $periodoe = $em->getRepository('App:Periodoe')->findBy(array('id' => $id));
        $periodoa = $em->getRepository('App:Periodoa')->findBy(array('periodoe' => $periodoe));
        $ofertas = $em->getRepository('App:Oferta')->findBy(array('curso' => $cursos, 'periodo' => $periodoa),array('director' => 'ASC'));

        return $this->render('coevalDirector/index.html.twig', array(
            'programas' => $programas,
            'cursos' => $cursos,
            'ofertas' => $ofertas,
            'periodoe' => $periodoe,
            'periodoa' => $periodoa
        ));
    }
    /**
     * Creates a new coevalDirector entity.
     *
     * @Route("/", name="coevaldirector_create",  methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new coevalDirector();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('coevaldirector_show', array('id' => $entity->getId())));
        }

        return $this->render('coevalDirector/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a coevalDirector entity.
    *
    * @param coevalDirector $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(coevalDirector $entity)
    {
        $form = $this->createForm(coevalDirectorType::class, $entity, array(
            'action' => $this->generateUrl('coevaldirector_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }


    /**
     * Finds and displays a coevalDirector entity.
     *
     * @Route("/{id}", name="coevaldirector_show",  methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:coevalDirector')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find coevalDirector entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('coevalDirector/show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing coevalDirector entity.
     *
     * @Route("/{id}/edit", name="coevaldirector_edit",  methods={"GET"})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('App:coevalDirector')->find($id);
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));

        if (!$entity) {
        $entity = new coevalDirector();
        $em = $this->doctrine->getManager();
        $oferta = $em->getRepository('App:Oferta')->find($id);
        $entity->setOferta($oferta);
        $em->persist($entity);
        $em->flush();
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('coevalDirector/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'periodo' => $periodo
        ));
    }

    /**
    * Creates a form to edit a coevalDirector entity.
    *
    * @param coevalDirector $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(coevalDirector $entity)
    {
        $form = $this->createForm(coevalDirectorType::class, $entity, array(
            'action' => $this->generateUrl('coevaldirector_update', array('id' => $entity->getOferta()->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing coevalDirector entity.
     *
     * @Route("/{id}", name="coevaldirector_update",  methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('App:coevalDirector')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find coevalDirector entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $entity->setFecha(new \DateTime());
        $suma = 0; $tot = 0;
        for($i=1; $i<18; $i++){
        if($editForm["f".$i]->getData()>0){
         $suma = $suma + $editForm["f".$i]->getData();
         $tot = $tot + 1;
        }
        }
       $entity->setF0($suma/$tot);

        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('docente_coevaldirector'));
        }

        return $this->render('coevalDirector/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a coevalDirector entity.
     *
     * @Route("/{id}", name="coevaldirector_delete",  methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:coevalDirector')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find coevalDirector entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('coevaldirector'));
    }

    /**
     * Creates a form to delete a coevalDirector entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('coevaldirector_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
