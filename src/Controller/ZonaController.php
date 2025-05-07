<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Zona;
use App\Form\ZonaType;

/**
 * Zona controller.
 *
 * @Route("/unad/zona")
 */
class ZonaController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Lists all Zona entities.
     *
     * @Route("/", name="zona", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->doctrine->getManager();

        $entities = $em->getRepository('App:Zona')->findAll();

        return $this->render('Zona/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Zona entity.
     * @Route("/", name="zona_create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new Zona();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('zona_show', array('id' => $entity->getId())));
        }

        return $this->render('Zona/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
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
        $form = $this->createForm(ZonaType::class, $entity, array(
            'action' => $this->generateUrl('zona_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Zona entity.
     *
     * @Route("/new", name="zona_new", methods={"GET"})
     */
    public function newAction()
    {
        $entity = new Zona();
        $form = $this->createCreateForm($entity);

        return $this->render('Zona/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Zona entity.
     *
     * @Route("/{id}", name="zona_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Zona')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Zona entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Zona/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Zona entity.
     *
     * @Route("/{id}/edit", name="zona_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Zona')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Zona entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm["director"]->setData($entity->getDirector()->getId());
        $deleteForm = $this->createDeleteForm($id);
        $editForm["director_nom"]->setData($entity->getDirector()->getNombres() . ' ' . $entity->getDirector()->getApellidos());

        return $this->render('Zona/edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
            ));
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
        $form = $this->createForm(ZonaType::class, $entity, array(
            'action' => $this->generateUrl('zona_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Edits an existing Zona entity.
     *
     * @Route("/{id}", name="zona_update", methods={"POST"})
     */
    public function updateAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Zona')->find($id);

        if (!$entity) {
            return new JsonResponse(array('message' => 'Unable to find Zona entity'), 500);
            // throw $this->createNotFoundException('Unable to find Zona entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $director = $em->getRepository('App:User')->find($editForm["director"]->getData());

        if (count($director) == 0) {
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
            $editForm["director_nom"]->setData($entity->getDirector()->getNombres() . ' ' . $entity->getDirector()->getApellidos());
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
     * @Route("/{id}", name="zona_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:Zona')->find($id);

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
            ->getForm();
    }


    /**
     * @Route("/docs/{id}", name="zona_index", methods={"GET"})
     */
    public function listaAction($id)
    {
        $em = $this->doctrine->getManager();
        $user = $this->getUser();
        $zona = $user->getDirectorzona();
        $year = $this->getParameter('appmed.year');
        $periodoss = $em->getRepository('App:Periodoe')->findby(array('type' => 's'), array('id' => 'DESC'), 10);
        $periodosa = $em->getRepository('App:Periodoe')->findby(array('type' => 'a'), array('id' => 'DESC'), 5);
        $periodosp = $em->getRepository('App:Periodoe')->findby(array('type' => 'p', 'year' => $year), array('id' => 'DESC'));
        if ($id == 1) {
            $id = $this->getParameter('appmed.periodo');
        }
        $centros = $em->getRepository('App:Centro')->findBy(array('zona' => $zona[0]));
        $docentes = $em->getRepository('App:Docente')->findBy(array('centro' => $centros, 'periodo' => $id));
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $id));


        return  $this->render('Zona/docs.html.twig', array(
            'docentes' => $docentes,
            'zona' => $zona[0],
            'periodoss' => $periodoss,
            'periodosa' => $periodosa,
            'periodosp' => $periodosp,
            'periodo' => $periodo,
            'year' => $year
        ));
    }
}
