<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Avalplang;
use App\Form\AvalplangType;


/**
 * Avalplang controller.
 *
 * @Route("/aval")
 */
class AvalplangController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Lists all Avalplang entities.
     *
     * @Route("/", name="avalplang", methods={"GET"})
     */
    public function indexAction(): Response
    {
        $em = $this->doctrine->getManager();

        $user = $this->getUser();
        $entities = $em->getRepository('App:Avalplang')->findby(array('user' => $user, 'periodo' => $this->getParameter('appmed.semestre')));
        return $this->render('Avalplang/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Lists all Avalplang entities.
     *
     * @Route("/lista", name="aval_lista", methods={"GET"})
     */
    public function listaAction(): Response
    {
        $em = $this->doctrine->getManager();

        $entities = $em->getRepository('App:Avalplang')
            ->findBy(
                array('periodo' => $this->getParameter('appmed.semestre'))
            );
        if (count($entities) == 0) {
            $this->addAvales();
        }

        return $this->render('Avalplang/lista.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Lists all Avalplang por escuela.
     *
     * @Route("/planesg", name="aval_porescuela", methods={"GET"})
     */
    public function porescuelaAction(Request $request)
    {
        $em = $this->doctrine->getManager();
        $session = $request->getSession();
        $escuelaid = $session->get('escuelaid');
        $semestre = $this->getParameter('appmed.semestre');
        $periodoe = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $semestre));
        if ($escuelaid == null) {
            return $this->redirect($this->generateUrl('home_user_inicio'));
        }

        if (!$this->isGranted('ROLE_ADMIN')) {
            $escuela = $em->getRepository('App:Escuela')->find($session->get('escuelaid'));
            $docentes = $em->getRepository('App:Docente')
                ->findBy(
                    array('periodo' => $semestre,
                        'vinculacion' => 'DC', 'escuela' => $escuela));
            return $this->render('Avalplang/porescuela.html.twig', array(
                'entities' => $docentes,
                'periodo' => $periodoe,
                'escuela' => $escuela
            ));
        } else {
            $escuela = null;
            $docentes = $em->getRepository('App:Docente')->findBy(
                array('periodo' => $semestre,
                    'vinculacion' => 'DC'));
            return $this->render('Avalplang/porescuela.html.twig', array(
                'entities' => $docentes,
                'periodo' => $semestre,
                'escuela' => $escuela
            ));
        }
    }


    /**
     * Avalar plang
     *
     * @Route("/planesg/{id}", name="aval_plangestion", methods={"GET"})
     */
    public function plangestionAction($id): Response
    {

        $em = $this->doctrine->getManager();

        $docente = $em->getRepository('App:Docente')->find($id);
        $periodoe = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $docente->getPeriodo()));
        $entity = $docente->getPlangestion();

        return $this->render('Avalplang/plangestion.html.twig', array(
            'docente' => $docente,
            'entity' => $entity,
            'periodo' => $periodoe
        ));
    }


    /**
     * Creates a new Avalplang entity.
     *
     * @Route("/", name="avalplang_create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new Avalplang();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('avalplang_show', array('id' => $entity->getId())));
        }

        return $this->render('Avalplang/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Avalplang entity.
     *
     * @param Avalplang $entity The entity
     *
     */
    private function createCreateForm(Avalplang $entity): FormInterface
    {
        $form = $this->createForm(AvalplangType::class, $entity, array(
            'action' => $this->generateUrl('avalplang_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Avalplang entity.
     *
     * @Route("/new", name="avalplang_new", methods={"GET"})
     */
    public function newAction(): Response
    {
        $entity = new Avalplang();
        $form = $this->createCreateForm($entity);

        return $this->render('Avalplang/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Avalplang entity.
     *
     * @Route("/{id}", name="avalplang_show", methods={"GET"})
     */
    public function showAction($id): Response
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Avalplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Avalplang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Avalplang/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Avalplang entity.
     *
     * @Route("/{id}/edit", name="avalplang_edit", methods={"GET"})
     */
    public function editAction($id): Response
    {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Avalplang')->find($id);

        $texto = explode('\n', $entity->getObservaciones());

        $editForm = $this->createEditForm($entity);

        return $this->render('Avalplang/edit.html.twig', array(
            'entity' => $entity,
            'texto' => $texto,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Avalplang entity.
     *
     * @param Avalplang $entity The entity
     *
     */
    private function createEditForm(Avalplang $entity): FormInterface
    {
        $form = $this->createForm(AvalplangType::class, $entity, array(
            'action' => $this->generateUrl('avalplang_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Avalplang entity.
     *
     * @Route("/{id}", name="avalplang_update", methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Avalplang')->find($id);
        $plan = $entity->getPlan();

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $date = (new \DateTime());
        if ($entity->getObservaciones() != null) {
            $texto = $entity->getObservaciones() . '|' . $date->format('Y-m-d H:i') . ' - ' . $editForm->get('observaciones')->getData();
        } else {
            $texto = $date->format('Y-m-d H:i') . ' - ' . $editForm->get('observaciones')->getData();
        }

        $entity->setObservaciones($texto);
        if ($editForm->get('avalado')->getData() == 1) {
            $entity->setFechaAval(new \DateTime());
        }
        if ($editForm->get('avalado')->getData() == 2) {
            $plan->setEstado(0);
            //        $this->enviarMail($entity);
        }

        if ($editForm->isValid()) {

            $em->flush();

            return $this->redirect($this->generateUrl('aval_porescuela'));
        }

        return $this->render('Avalplang/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Avalplang entity.
     *
     * @Route("/{id}", name="avalplang_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:Avalplang')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Avalplang entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('avalplang'));
    }

    /**
     * Creates a form to delete a Avalplang entity by id.
     *
     * @param mixed $id The entity id
     *
     */
    private function createDeleteForm($id): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('avalplang_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    public function enviarMail(\App\Entity\Avalplang $aval, MailerInterface $mailer)
    {
        $docente = $aval->getPlan()->getDocente();
        $message = \Swift_Message::newInstance()
            ->setSubject('Plan de Gestión ' . $docente->getUser()->getId() . '-' . $docente->getPeriodo())
            ->setFrom(array('siga@unad.edu.co' => 'Módulo de Evaluación Docente MED'))
            ->setTo(array($docente->getUser()->getEmail() => $docente->getUser()->getNombres() . ' ' . $docente->getUser()->getApellidos()))
            ->setBody(
                $this->renderView('App:Avalplang:noaprobado.txt.twig', array('aval' => $aval)
                )
            );
        try {
            $mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            return 'Error';
        }
        return null;
    }

    public function addAvales()
    {
        $em = $this->doctrine->getManager();
        $semestre = $this->getParameter('appmed.semestre');
        $docentes = $em->getRepository('App:Docente')->findBy(array('periodo' => $semestre, 'vinculacion' => 'DC'));

        foreach ($docentes as $docente) {
            //agregar avalador Decano N
            $aval = new Avalplang();
            $aval->setPlan($docente->getPlangestion());
            $aval->setUser($docente->getEscuela()->getDecano());
            $aval->setPerfil('DECN');
            $aval->setPeriodo($semestre);
            $em->persist($aval);
            //agregar avalador Director de Zona
            if ($docente->getCentro()->getId() != 89999) {
                $aval1 = new Avalplang();
                $aval1->setPlan($docente->getPlangestion());
                $aval1->setUser($docente->getCentro()->getZona()->getDirector());
                $aval1->setPerfil('DIRZ');
                $aval1->setPeriodo($semestre);
                $em->persist($aval1);
            }
            $em->flush();
        }
    }

}
