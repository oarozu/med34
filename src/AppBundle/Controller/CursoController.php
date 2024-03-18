<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Rolplang;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Curso;
use AppBundle\Entity\Oferta;
use AppBundle\Entity\Cedula;
use AppBundle\Entity\Tutor;
use AppBundle\Form\CursoType;
use AppBundle\Form\ofertaType;
use AppBundle\Form\CedulaTypeMed;
use AppBundle\Form\CursoprogType;

/**
 * Curso controller.
 *
 * @Route("/unad/curso")
 */
class CursoController extends AbstractController
{

    /**
     * Lists all Curso entities.
     *
     * @Route("/", name="curso", methods={"GET"})
     * @Template("Curso/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Curso')->findAll();
        return array(
            'entities' => $entities,
            'sigla' => false
        );
    }


    /**
     * Lists all Curso entities.
     *
     * @Route("/pe/{id}", name="curso_escuela", methods={"GET"})
     * @Template("Curso/index.html.twig")
     *
     */
    public function porescuelaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $escuela = $em->getRepository('AppBundle:Escuela')->findOneBy(array('id' => $id));
        $sigla = $escuela->getSigla();
        $entities = $em->getRepository('AppBundle:Curso')->findBy(array('escuela' => $sigla));

        return array(
            'entities' => $entities,
            'sigla' => $sigla
        );
    }


    /**
     * Creates a new Curso entity.
     *
     * @Route("/", name="curso_create", methods={"POST"})
     * @Template("Curso/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Curso();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('curso_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Curso entity.
     *
     * @param Curso $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Curso $entity)
    {
        $form = $this->createForm(CursoType::class, $entity, array(
            'action' => $this->generateUrl('curso_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Curso entity.
     *
     * @Route("/new", name="curso_new", methods={"GET"})
     * @Template("Curso/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Curso();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Curso entity
     * @Route("/{id}", name="curso_show", methods={"GET"})
     * @Template("Curso/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Curso')->find($id);
        $peracas = $em->getRepository('AppBundle:Periodoe')->findLastThree();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $periodos = $em->getRepository('AppBundle:Periodoa')->findby(array('periodoe' => $this->container->getParameter('appmed.periodo')));
        $cursooferta = $em->getRepository('AppBundle:Oferta')->findby(array('periodo' => $periodos, 'curso' => $entity));

        $datos = new \AppBundle\Entity\OfertaDatos();
        $Form = $this->createForm(ofertaType::class, $datos, array(
            'action' => $this->generateUrl('oferta_curso', array('id' => $entity->getId())),
            'method' => 'POST',
            'peracas' => $peracas
        ));

        return array(
            'entity' => $entity,
            'cursooferta' => $cursooferta,
            'form' => $Form->createView(),
        );
    }

    /**
     * Docentes de un curso por oferta
     * @Route("/{id}/oferta", name="oferta", methods={"GET"})
     * @Template("Curso/oferta.html.twig")
     */

    public function ofertaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }
        $cedula = new Cedula();
        $Form = $this->createForm(CedulaTypeMed::class, $cedula, array(
            'action' => $this->generateUrl('oferta_tutor', array('id' => $entity->getId())),
            'method' => 'POST',
        ));
        return array(
            'entity' => $entity,
            'cedula_form' => $Form->createView(),
        );
    }


    /**
     * Finds and displays a Curso entity
     * @Route("/{id}/addoferta", name="oferta_curso", methods={"POST"})
     */
    public function ofertaCursoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $curso = $em->getRepository('AppBundle:Curso')->find($id);
        $oferta = new Oferta();
        $datos = $request->request->get('oferta');
        $usuario = $em->getRepository('AppBundle:User')->find($datos['cedula']);
        if (!$usuario) {
            $this->get('session')->getFlashBag()->add('error', 'Cédula no encontrada');
            return $this->redirect($this->generateUrl('curso_show', array('id' => $id)));
        }

        $docente = $em->getRepository('AppBundle:Docente')->findOneBy(array('user' => $usuario, 'periodo' => $this->container->getParameter('appmed.periodo')));

        if (!$docente) {
            $this->get('session')->getFlashBag()->add('error', 'El número no corresponde a un docente');
            return $this->redirect($this->generateUrl('curso_show', array('id' => $id)));
        }

        $oferta->setCurso($curso);
        $oferta->setDirector($docente);
        $oferta->setPeriodo($datos['periodo']);

        try {
            $em->persist($oferta);
            $em->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->get('session')->getFlashBag()->add('warning', 'Error de base de datos');
            return $this->redirect($this->generateUrl('curso_show', array('id' => $id)));
        }
        $this->get('session')->getFlashBag()->add('success', 'La oferta se agrego al curso');
        return $this->redirect($this->generateUrl('curso_show', array('id' => $id)));
    }


    /**
     * Finds and displays a Curso entity
     * @Route("/{id}/tutor", name="oferta_tutor", methods={"POST"})
     */
    public function ofertaTutorAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('AppBundle:Oferta')->find($id);
        $data = new Cedula();
        $form = $this->createForm(CedulaTypeMed::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $director = $em->getRepository('AppBundle:Docente')->findOneBy(array('user' => $user, 'periodo' => $this->container->getParameter('appmed.periodo')));

        if ($oferta->getDirector() != $director && !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'No permitido no es director');
            return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
        }

        $usuario = $em->getRepository('AppBundle:User')->find($data->getCedula());
        if (!$usuario) {
            $this->get('session')->getFlashBag()->add('error', 'Cédula no encontrada');
            return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
        }

        $docente = $em->getRepository('AppBundle:Docente')->findOneBy(array('user' => $usuario, 'periodo' => $this->container->getParameter('appmed.periodo')));

        if (!$docente) {
            $this->get('session')->getFlashBag()->add('error', 'El número no corresponde a un docente');
            return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
        }

        if ($docente->getId() == $oferta->getDirector()->getId()) {
            $this->get('session')->getFlashBag()->add('warning', 'No es necesario que el director se agregue como tutor');
            return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
        }

        $tutor = new Tutor();
        $tutor->setOferta($oferta);
        $tutor->setDocente($docente);
        try {
            $em->persist($tutor);
            $em->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->get('session')->getFlashBag()->add('warning', 'El docente ya se encuentra en el curso');
            return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
        }
        $this->get('session')->getFlashBag()->add('success', 'El docente se agrego al curso');
        return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
    }


    /** en modal
     * @Route("/{id}/modal", name="curso_modal", methods={"GET"})
     * @Template("Curso/modal.html.twig")
     */
    public function modalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Oferta')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $cedula = new Cedula();
        $Form = $this->createForm(CedulaTypeMed::class, $cedula, array(
            'action' => $this->generateUrl('oferta_tutor', array('id' => $id)),
            'method' => 'GET',
        ));
        return array(
            'entity' => $entity,
            'cedula_form' => $Form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Curso entity.
     *
     * @Route("/{id}/edit", name="curso_edit", methods={"GET"})
     * @Template("Curso/edit.html.twig")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $escuelaid = $session->get('escuelaid');
        $entity = $em->getRepository('AppBundle:Curso')->find($id);
        $siglas = $em->getRepository('AppBundle:Escuela')->findSiglas();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $editForm = $this->createEditarForm($entity, $escuelaid, $siglas);

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    public function getArray($values){
        $result = array();
        foreach ($values as $i => $value){
            $result[$i] = $value['sigla'];
        }
        return $result;
    }

    /**
     * Displays a form to edit an existing Curso entity.
     * @Route("/{id}/ofertaedit", name="oferta_edit", methods={"GET"})
     * @Template("Curso/ofertaedit.html.twig")
     */
    public function ofertaeditAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Oferta')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('NO se encontro oferta');
        }
        $cedula = new Cedula();
        $Form = $this->createForm(CedulaTypeMed::class, $cedula, array(
            'action' => $this->generateUrl('oferta_update', array('id' => $entity->getId())),
            'method' => 'GET',
        ));
        return array(
            'entity' => $entity,
            'cedula_form' => $Form->createView(),
        );
    }


    /**
     * Creates a form to edit a Curso entity.
     * @param Curso $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Curso $entity)
    {
        $form = $this->createForm(CursoType::class, $entity, array(
            'action' => $this->generateUrl('curso_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Update'));
        return $form;
    }


    /**
     * Creates a form to edit a Curso entity.
     *
     * @param Curso $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditarForm(Curso $entity, $escuelaid, $siglas)
    {
        $form = $this->createForm(CursoprogType::class, $entity, array(
                'action' => $this->generateUrl('curso_update', array('id' => $entity->getId())),
                'method' => 'PUT',
                'escuela' => $escuelaid,
                'siglas' => $siglas
            )
        );
        $form->add('submit', SubmitType::class, array('label' => 'Actualizar'));
        return $form;

    }

    /**
     * Edits an existing Curso entity.
     * @Route("/{id}/ofertaupdate", name="oferta_update", methods={"GET"})
     * @Template("Curso/ofertaedit.html.twig")
     */
    public function ofertaupdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('AppBundle:Oferta')->find($id);
        $cedula = new Cedula();
        $Form = $this->createForm(CedulaTypeMed::class, $cedula, array(
            'action' => $this->generateUrl('oferta_update', array('id' => $id)),
            'method' => 'GET',
        ));
        $Form->handleRequest($request);

        $ncedula = $Form->get('cedula')->getData();
        $user = $em->getRepository('AppBundle:User')->find($ncedula);
        $docente = $em->getRepository('AppBundle:Docente')->findOneBy(array('user' => $user, 'periodo' => $this->container->getParameter('appmed.periodo')));
        if (!$docente) {
            $this->get('session')->getFlashBag()->add('error', 'El número no corresponde a un docente');
            return $this->redirect($this->generateUrl('oferta_edit', array('id' => $id)));
        }
        $plang = $em->getRepository('AppBundle:Plangestion')->findOneBy(array('docente' => $docente));
        $rolAcademico = $em->getRepository('AppBundle:Rolacademico')->findOneBy(array('id' => 2));
        $esDirector = $em->getRepository('AppBundle:Rolplang')->findOneBy(array('rol' => $rolAcademico, 'plang' => $plang));
        if ($esDirector == null){
            $rolDirector = new Rolplang();
            $rolDirector->setPlang($plang);
            $rolDirector->setRol($rolAcademico);
            $em->persist($rolDirector);
        }
        $oferta->setDirector($docente);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'Se actualizo el director');
        return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
    }

    /**
     * Edits an existing Curso entity.
     *
     * @Route("/{id}", name="curso_update", methods={"PUT"})
     * @Template("Curso/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $escuelaid = $session->get('escuelaid');
        $entity = $em->getRepository('AppBundle:Curso')->find($id);
        $siglas = $em->getRepository('AppBundle:Escuela')->findSiglas();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditarForm($entity, $escuelaid, $siglas);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('lider_cursos', array('id' => $escuelaid)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Deletes a Curso entity.
     *
     * @Route("/{id}", name="curso_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Curso')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Curso entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('curso'));
    }

    /**
     * Creates a form to delete a Curso entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('curso_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //          ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * Borrar Tutor.
     *
     * @Route("/tutor/{id}/del", name="tutor_delete", methods={"GET"})
     */
    public function borrartutorAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Tutor')->find($id);
        $oferta = $entity->getOferta();
        $director = $oferta->getDirector();
        $session = $request->getSession();

        if ($director->getId() == $session->get('docenteid') || $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            try {
                $em->remove($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Se borro el docente del curso');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->get('session')->getFlashBag()->add('warning', 'El tutor no se puede remover ya evaluo');
                return $this->redirect($this->generateUrl('oferta', array('id' => $oferta->getId())));
            }
            return $this->redirect($this->generateUrl('oferta', array('id' => $oferta->getId())));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'No permitido');
            return $this->redirect($this->generateUrl('oferta', array('id' => $oferta->getId())));
        }

    }


}
