<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Avalplang;
use App\Form\AvalplangType;
use Symfony\Component\Security\Core\Security;


/**
 * Avalplang controller.
 *
 * @Route("/aval")
 */
class AvalplangController extends AbstractController
{

    /**
     * Lists all Avalplang entities.
     *
     * @Route("/", name="avalplang", methods={"GET"})
     * @Template("Avalplang/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $entities = $em->getRepository('AppBundle:Avalplang')->findby(array('user' => $user, 'periodo' => $this->getParameter('appmed.semestre')));
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all Avalplang entities.
     *
     * @Route("/lista", name="aval_lista", methods={"GET"})
     * @Template("Avalplang/lista.html.twig")
     */
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Avalplang')
            ->findBy(
                array('periodo' => $this->getParameter('appmed.semestre'))
            );
        if (count($entities) == 0) {
            $this->addAvales();
        }

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all Avalplang por escuela.
     *
     * @Route("/planesg", name="aval_porescuela", methods={"GET"})
     * @Template("Avalplang/porescuela.html.twig")
     */
    public function porescuelaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $escuelaid = $session->get('escuelaid');
        $semestre = $this->getParameter('appmed.semestre');
        $periodoe = $em->getRepository('AppBundle:Periodoe')->findOneBy(array('id' => $semestre));
        if ($escuelaid == null) {
            return $this->redirect($this->generateUrl('home_user_inicio'));
        }

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $escuela = $em->getRepository('AppBundle:Escuela')->find($session->get('escuelaid'));
            $docentes = $em->getRepository('AppBundle:Docente')
                ->findBy(
                    array('periodo' => $semestre,
                        'vinculacion' => 'DC', 'escuela' => $escuela));
            return array(
                'entities' => $docentes,
                'periodo' => $periodoe,
                'escuela' => $escuela
            );
        } else {
            $escuela = null;
            $docentes = $em->getRepository('AppBundle:Docente')->findBy(
                array('periodo' => $semestre,
                    'vinculacion' => 'DC'));
            return array(
                'entities' => $docentes,
                'periodo' => $semestre,
                'escuela' => $escuela
            );
        }

    }


    /**
     * Avalar plang
     *
     * @Route("/planesg/{id}", name="aval_plangestion", methods={"GET"})
     * @Template("Avalplang/plangestion.html.twig")
     */
    public function plangestionAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $docente = $em->getRepository('AppBundle:Docente')->find($id);
        $periodoe = $em->getRepository('AppBundle:Periodoe')->findOneBy(array('id' => $docente->getPeriodo()));
        $entity = $docente->getPlangestion();

        return array(
            'docente' => $docente,
            'entity' => $entity,
            'periodo' => $periodoe
        );
    }


    /**
     * Creates a new Avalplang entity.
     *
     * @Route("/", name="avalplang_create", methods={"POST"})
     * @Template("Avalplang/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Avalplang();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('avalplang_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Avalplang entity.
     *
     * @param Avalplang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Avalplang $entity)
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
     * @Template("Avalplang/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Avalplang();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Avalplang entity.
     *
     * @Route("/{id}", name="avalplang_show", methods={"GET"})
     * @Template("Avalplang/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Avalplang')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Avalplang entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Avalplang entity.
     *
     * @Route("/{id}/edit", name="avalplang_edit", methods={"GET"})
     * @Template("Avalplang/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Avalplang')->find($id);

        $texto = explode('\n', $entity->getObservaciones());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Avalplang entity.');
        }
        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'texto' => $texto,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Avalplang entity.
     *
     * @param Avalplang $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Avalplang $entity)
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
     * @Template("Avalplang/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Avalplang')->find($id);
        $plan = $entity->getPlan();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Avalplang entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($entity->getObservaciones() != null) {
            $date = (new \DateTime());
            $texto = $entity->getObservaciones() . '|' . $date->format('Y-m-d H:i') . ' - ' . $editForm->get('observaciones')->getData();
        } else {
            $date = (new \DateTime());
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

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
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
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Avalplang')->find($id);

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
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('avalplang_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    public function enviarMail(\App\Entity\Avalplang $aval)
    {
        $docente = $aval->getPlan()->getDocente();
        $message = \Swift_Message::newInstance()
            ->setSubject('Plan de Gestión ' . $docente->getUser()->getId() . '-' . $docente->getPeriodo())
            ->setFrom(array('siga@unad.edu.co' => 'Módulo de Evaluación Docente MED'))
            ->setTo(array($docente->getUser()->getEmail() => $docente->getUser()->getNombres() . ' ' . $docente->getUser()->getApellidos()))
            ->setBody(
                $this->renderView('AppBundle:Avalplang:noaprobado.txt.twig', array('aval' => $aval)
                )
            );
        $this->get('mailer')->send($message);
    }

    public function addAvales()
    {
        $em = $this->getDoctrine()->getManager();
        $semestre = $this->getParameter('appmed.semestre');
        $docentes = $em->getRepository('AppBundle:Docente')->findBy(array('periodo' => $semestre, 'vinculacion' => 'DC'));

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
