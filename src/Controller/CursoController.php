<?php

namespace App\Controller;

use App\Entity\Rolplang;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Curso;
use App\Entity\Oferta;
use App\Entity\Cedula;
use App\Entity\Tutor;
use App\Form\CursoType;
use App\Form\ofertaType;
use App\Form\CedulaTypeMed;
use App\Form\CursoprogType;

/**
 * Curso controller.
 *
 * @Route("/unad/curso")
 */
class CursoController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    /**
     * Lists all Curso entities.
     *
     * @Route("/", name="curso", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->doctrine->getManager();
        $entities = $em->getRepository('App:Curso')->findAll();
        return $this->render('Curso/index.html.twig', array(
            'entities' => $entities,
            'sigla' => false
        ));
    }


    /**
     * Lists all Curso entities.
     *
     * @Route("/pe/{id}", name="curso_escuela", methods={"GET"})
     *
     */
    public function porescuelaAction($id)
    {
        $em = $this->doctrine->getManager();
        $escuela = $em->getRepository('App:Escuela')->findOneBy(array('id' => $id));
        $sigla = $escuela->getSigla();
        $entities = $em->getRepository('App:Curso')->findBy(array('escuela' => $sigla));

        return $this->render('Curso/index.html.twig', array(
            'entities' => $entities,
            'sigla' => $sigla
        ));
    }


    /**
     * Creates a new Curso entity.
     *
     * @Route("/", name="curso_create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new Curso();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('curso_show', array('id' => $entity->getId())));
        }

        return $this->render('Curso/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
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
     */
    public function newAction()
    {
        $entity = new Curso();
        $form = $this->createCreateForm($entity);

        return $this->render('Curso/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Curso entity
     * @Route("/{id}", name="curso_show", methods={"GET"})
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository('App:Curso')->find($id);
        $peracas = $em->getRepository('App:Periodoe')->findLastThree();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $periodos = $em->getRepository('App:Periodoa')->findby(array('periodoe' => $this->getParameter('appmed.periodo')));
        $cursooferta = $em->getRepository('App:Oferta')->findby(array('periodo' => $periodos, 'curso' => $entity));

        $datos = new \App\Entity\OfertaDatos();
        $Form = $this->createForm(ofertaType::class, $datos, array(
            'action' => $this->generateUrl('oferta_curso', array('id' => $entity->getId())),
            'method' => 'POST',
            'peracas' => $peracas
        ));

        return $this->render('Curso/show.html.twig', array(
            'entity' => $entity,
            'cursooferta' => $cursooferta,
            'form' => $Form->createView(),
        ));
    }

    /**
     * Docentes de un curso por oferta
     * @Route("/{id}/oferta", name="oferta", methods={"GET"})
     */

    public function ofertaAction($id)
    {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }
        $cedula = new Cedula();
        $Form = $this->createForm(CedulaTypeMed::class, $cedula, array(
            'action' => $this->generateUrl('oferta_tutor', array('id' => $entity->getId())),
            'method' => 'POST',
        ));
        return $this->render('Curso/oferta.html.twig', array(
            'entity' => $entity,
            'cedula_form' => $Form->createView(),
        ));
    }


    /**
     * Finds and displays a Curso entity
     * @Route("/{id}/addoferta", name="oferta_curso", methods={"POST"})
     */
    public function ofertaCursoAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $curso = $em->getRepository('App:Curso')->find($id);
        $oferta = new Oferta();
        $datos = $request->request->get('oferta');
        $usuario = $em->getRepository('App:User')->find($datos['cedula']);
        if (!$usuario) {
            $request->getSession()->getFlashBag()->add('error', 'Cédula no encontrada');
            return $this->redirect($this->generateUrl('curso_show', array('id' => $id)));
        }

        $docente = $em->getRepository('App:Docente')->findOneBy(array('user' => $usuario, 'periodo' => $this->getParameter('appmed.periodo')));

        if (!$docente) {
            $request->getSession()->getFlashBag()->add('error', 'El número no corresponde a un docente');
            return $this->redirect($this->generateUrl('curso_show', array('id' => $id)));
        }

        $oferta->setCurso($curso);
        $oferta->setDirector($docente);
        $oferta->setPeriodo($datos['periodo']);

        try {
            $em->persist($oferta);
            $em->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
            $request->getSession()->getFlashBag()->add('warning', 'Error de base de datos');
            return $this->redirect($this->generateUrl('curso_show', array('id' => $id)));
        }
        $request->getSession()->getFlashBag()->add('success', 'La oferta se agrego al curso');
        return $this->redirect($this->generateUrl('curso_show', array('id' => $id)));
    }


    /**
     * Finds and displays a Curso entity
     * @Route("/{id}/tutor", name="oferta_tutor", methods={"POST"})
     */
    public function ofertaTutorAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $oferta = $em->getRepository('App:Oferta')->find($id);
        $data = new Cedula();
        $form = $this->createForm(CedulaTypeMed::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $user = $this->getUser();
        $director = $em->getRepository('App:Docente')->findOneBy(array('user' => $user, 'periodo' => $this->getParameter('appmed.periodo')));

        if ($oferta->getDirector() != $director && !$this->isGranted('ROLE_ADMIN')) {
            $request->getSession()->getFlashBag()->add('error', 'No permitido no es director');
            return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
        }

        $usuario = $em->getRepository('App:User')->find($data->getCedula());
        if (!$usuario) {
            $request->getSession()->getFlashBag()->add('error', 'Cédula no encontrada');
            return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
        }

        $docente = $em->getRepository('App:Docente')->findOneBy(array('user' => $usuario, 'periodo' => $this->getParameter('appmed.periodo')));

        if (!$docente) {
            $request->getSession()->getFlashBag()->add('error', 'El número no corresponde a un docente');
            return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
        }

        if ($docente->getId() == $oferta->getDirector()->getId()) {
            $request->getSession()->getFlashBag()->add('warning', 'No es necesario que el director se agregue como tutor');
            return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
        }

        $tutor = new Tutor();
        $tutor->setOferta($oferta);
        $tutor->setDocente($docente);
        try {
            $em->persist($tutor);
            $em->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
            $request->getSession()->getFlashBag()->add('warning', 'El docente ya se encuentra en el curso');
            return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
        }
        $request->getSession()->getFlashBag()->add('success', 'El docente se agrego al curso');
        return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
    }


    /** en modal
     * @Route("/{id}/modal", name="curso_modal", methods={"GET"})
     */
    public function modalAction($id)
    {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Oferta')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $cedula = new Cedula();
        $Form = $this->createForm(CedulaTypeMed::class, $cedula, array(
            'action' => $this->generateUrl('oferta_tutor', array('id' => $id)),
            'method' => 'GET',
        ));
        return $this->render('Curso/modal.html.twig', array(
            'entity' => $entity,
            'cedula_form' => $Form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Curso entity.
     *
     * @Route("/{id}/edit", name="curso_edit", methods={"GET"})
     */
    public function editAction($id, Request $request)
    {
        $em = $this->doctrine->getManager();
        $session = $request->getSession();
        $escuelaid = $session->get('escuelaid');
        $entity = $em->getRepository('App:Curso')->find($id);
        $siglas = $em->getRepository('App:Escuela')->findSiglas();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }

        $editForm = $this->createEditarForm($entity, $escuelaid, $siglas);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Curso/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
     */
    public function ofertaeditAction($id)
    {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Oferta')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('NO se encontro oferta');
        }
        $cedula = new Cedula();
        $Form = $this->createForm(CedulaTypeMed::class, $cedula, array(
            'action' => $this->generateUrl('oferta_update', array('id' => $entity->getId())),
            'method' => 'GET',
        ));
        return $this->render('Curso/ofertaedit.html.twig', array(
            'entity' => $entity,
            'cedula_form' => $Form->createView(),
        ));
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
     */
    public function ofertaupdateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $oferta = $em->getRepository('App:Oferta')->find($id);
        $cedula = new Cedula();
        $Form = $this->createForm(CedulaTypeMed::class, $cedula, array(
            'action' => $this->generateUrl('oferta_update', array('id' => $id)),
            'method' => 'GET',
        ));
        $Form->handleRequest($request);

        $ncedula = $Form->get('cedula')->getData();
        $user = $em->getRepository('App:User')->find($ncedula);
        $docente = $em->getRepository('App:Docente')->findOneBy(array('user' => $user, 'periodo' => $this->getParameter('appmed.periodo')));
        if (!$docente) {
            $request->getSession()->getFlashBag()->add('error', 'El número no corresponde a un docente');
            return $this->redirect($this->generateUrl('oferta_edit', array('id' => $id)));
        }
        $plang = $em->getRepository('App:Plangestion')->findOneBy(array('docente' => $docente));
        $rolAcademico = $em->getRepository('App:Rolacademico')->findOneBy(array('id' => 2));
        $esDirector = $em->getRepository('App:Rolplang')->findOneBy(array('rol' => $rolAcademico, 'plang' => $plang));
        if ($esDirector == null){
            $rolDirector = new Rolplang();
            $rolDirector->setPlang($plang);
            $rolDirector->setRol($rolAcademico);
            $em->persist($rolDirector);
        }
        $oferta->setDirector($docente);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Se actualizo el director');
        return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
    }

    /**
     * Edits an existing Curso entity.
     *
     * @Route("/{id}", name="curso_update", methods={"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $session = $request->getSession();
        $escuelaid = $session->get('escuelaid');
        $entity = $em->getRepository('App:Curso')->find($id);
        $siglas = $em->getRepository('App:Escuela')->findSiglas();
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

        return $this->render('Curso/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
            $em = $this->doctrine->getManager();
            $entity = $em->getRepository('App:Curso')->find($id);

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
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:Tutor')->find($id);
        $oferta = $entity->getOferta();
        $director = $oferta->getDirector();
        $session = $request->getSession();

        if ($director->getId() == $session->get('docenteid') || $this->isGranted('ROLE_ADMIN')) {
            try {
                $em->remove($entity);
                $em->flush();
                $request->getSession()->getFlashBag()->add('success', 'Se borro el docente del curso');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $request->getSession()->getFlashBag()->add('warning', 'El tutor no se puede remover ya evaluo');
                return $this->redirect($this->generateUrl('oferta', array('id' => $oferta->getId())));
            }
            return $this->redirect($this->generateUrl('oferta', array('id' => $oferta->getId())));
        } else {
            $request->getSession()->getFlashBag()->add('error', 'No permitido');
            return $this->redirect($this->generateUrl('oferta', array('id' => $oferta->getId())));
        }
    }
}
