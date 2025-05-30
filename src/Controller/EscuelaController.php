<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Escuela;
use App\Form\EscuelaType;

/**
 * Escuela controller.
 *
 * @Route("/unad/escuela")
 */
class EscuelaController extends AbstractController
{

    /**
     * Lists all Escuela entities.
     *
     * @Route("/", name="escuela", methods={"GET"})
     */
    public function indexAction(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();

        $entities = $em->getRepository('App:Escuela')->findAll();

        return $this->render('Escuela/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Escuela entity.
     *
     * @Route("/", name="escuela_create", methods={"POST"})
     */
    public function createAction(Request $request, ManagerRegistry $doctrine)
    {
        $entity = new Escuela();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $em = $doctrine->getManager();
        $decano = $em->getRepository('App:User')->find($form["decano"]->getData());
        $entity->setDecano($decano);
        $secretaria = $em->getRepository('App:User')->find($form["secretaria"]->getData());
        $entity->setSecretaria($secretaria);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('escuela_show', array('id' => $entity->getId())));
        }

        return $this->render('Escuela/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Escuela entity.
     *
     * @param Escuela $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Escuela $entity)
    {
        $form = $this->createForm(EscuelaType::class, $entity, array(
            'action' => $this->generateUrl('escuela_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Escuela entity.
     *
     * @Route("/new", name="escuela_new", methods={"GET"})
     */
    public function newAction()
    {
        $entity = new Escuela();
        $form = $this->createCreateForm($entity);

        return $this->render('Escuela/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Escuela entity.
     *
     * @Route("/{id}", name="escuela_show", methods={"GET"})
     */
    public function showAction($id, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();

        $entity = $em->getRepository('App:Escuela')->find($id);
        $terna = $em->getRepository('App:Terna')->findBy(array('escuela' => $entity, 'periodo' => $this->getParameter('appmed.periodo')));


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Escuela/show.html.twig', array(
            'entity' => $entity,
            'terna' => $terna,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Escuela entity.
     *
     * @Route("/mi/info", name="escuela_info", methods={"GET"})
     */
    public function infoAction(Request $request, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();

        $year = $this->getParameter('appmed.year');
        $session = $request->getSession();
        $escuelaid = $session->get('escuelaid');
        if ($escuelaid != null){
            $escuela = $em->getRepository('App:Escuela')->find($escuelaid);
        }else{
            return $this->redirect($this->generateUrl('home_user_inicio'));
        }

        $periodoss = $em->getRepository('App:Periodoe')->findby(array('type' => 's'), array('id' => 'DESC'), 14);
        $periodosa = $em->getRepository('App:Periodoe')->findby(array('type' => 'a'), array('id' => 'DESC'), 5);
        $periodosp = $em->getRepository('App:Periodoe')->findby(array('type' => 'p'), array('id' => 'DESC'), 10);

        $programas = $em->getRepository('App:Programa')->findBy(array('escuela' => $escuela), array('nivel' => 'DESC'));
        $periodosession = $session->get('periodoe');
        if (!$periodosession){
            return $this->redirect($this->generateUrl('home_user_inicio'));
        }
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));

        $ofertado = $em->getRepository('App:ProgramaPeriodo')->findby(array('programa' => $programas, 'periodo' => $session->get('periodoe')));


        if (!$escuela) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }
        return $this->render('Escuela/info.html.twig', array(
            'entity' => $escuela,
            'ofertado' => $ofertado,
            'periodoss' => $periodoss,
            'periodosa' => $periodosa,
            'periodosp' => $periodosp,
            'periodo' => $periodo,
            'year' => $year
        ));
    }

    /**
     * Displays a form to edit an existing Escuela entity.
     *
     * @Route("/{id}/edit", name="escuela_edit", methods={"GET"})
     */
    public function editAction($id, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();

        $entity = $em->getRepository('App:Escuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm["decano"]->setData($entity->getDecano()->getId());
        $editForm["secretaria"]->setData($entity->getSecretaria()->getId());
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Escuela/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Escuela entity.
     *
     * @param Escuela $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Escuela $entity)
    {
        $form = $this->createForm(EscuelaType::class, $entity, array(
            'action' => $this->generateUrl('escuela_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Escuela entity.
     *
     * @Route("/{id}", name="escuela_update", methods={"PUT"})
     */
    public function updateAction(Request $request, $id, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();

        $entity = $em->getRepository('App:Escuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $decano = $em->getRepository('App:User')->find($editForm["decano"]->getData());
        $entity->setDecano($decano);
        $secretaria = $em->getRepository('App:User')->find($editForm["secretaria"]->getData());
        $entity->setSecretaria($secretaria);

        if ($editForm->isValid() && $secretaria && $decano) {
            $em->flush();

            return $this->redirect($this->generateUrl('escuela_edit', array('id' => $id)));
        }

        return $this->render('Escuela/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Escuela entity.
     *
     * @Route("/{id}", name="escuela_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id, ManagerRegistry $doctrine)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $doctrine->getManager();
            $entity = $em->getRepository('App:Escuela')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Escuela entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('escuela'));
    }

    /**
     * Creates a form to delete a Escuela entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('escuela_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * Finds and displays a Escuela entity.
     *
     */
    public function coevalliderAction(Request $request, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $session = $request->getSession();
        $escuela = $em->getRepository('App:Escuela')->find($session->get('escuelaid'));
        if (!$escuela) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }
        return $this->render('Escuela/coevallider.html.twig', array(
            'entity' => $escuela,
        ));
    }


    /**
     * Lista la evaluacion de estudiantes
     * @Route("/mi/heteroeval", name="escuela_heteroeval", methods={"GET"})
     */
    public function heteroevalAction(Request $request, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $session = $request->getSession();
        $escuela = $em->getRepository('App:Escuela')->find($session->get('escuelaid'));
        $docentes = $em->getRepository('App:Docente')->findby(array('escuela' => $session->get('escuelaid')));
        if (!$escuela) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }
        return $this->render('Escuela/heteroeval.html.twig', array(
            'escuela' => $escuela,
            'docentes' => $docentes,
        ));
    }


    /**
     * @Route("/mi/resultados", name="escuela_resultados", methods={"GET"})
     */
    public function resultadosAction(Request $request, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $session = $request->getSession();
        $escuela = $em->getRepository('App:Escuela')->find($session->get('escuelaid'));
        if (!$escuela) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }
        return $this->render('Escuela/resultados.html.twig', array(
            'escuela' => $escuela,
        ));
    }
}
