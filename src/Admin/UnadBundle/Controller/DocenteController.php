<?php

namespace Admin\UnadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\UnadBundle\Entity\Docente;
use Admin\UnadBundle\Entity\Escuela;
use Admin\MedBundle\Entity\Instrumento;
use Admin\MedBundle\Entity\Evaluacion;
use Admin\UnadBundle\Form\DocenteType;
use Admin\UnadBundle\Form\ObservType;

/**
 * Docente controller
 * @Route("/unad/doc")
 */
class DocenteController extends Controller
{

    /**
     * Lists all Docente entities.
     *
     * @Route("/pe/{periodo}", name="docente")
     * @Method("GET")
     * @Template("Docente/porperiodo.html.twig")
     */
    public function indexAction($periodo)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AdminUnadBundle:Docente')->docentesPeriodo($periodo);
        return array(
            'entities' => $entities,
            'periodo' => $periodo
        );
    }

    /**
     * Home de Docentes
     * @Route("/home/{periodo}", name="docente_home")
     * @Method("GET")
     * @Template("Docente/home.html.twig")
     */
    public function homeAction($periodo)
    {
        $em = $this->getDoctrine()->getManager();
        //$periodo = $this->container->getParameter('appmed.periodo');
        $escuelas = $em->getRepository('AdminUnadBundle:Escuela')->findAll();
        $periodose = $em->getRepository('AdminMedBundle:Periodoe')->findby(array(), array('id' => 'DESC'));
        $docsdc = $em->getRepository('AdminUnadBundle:Docente')->totalEscuelas($periodo);
        return array(
            'escuelas' => $escuelas,
            'periodo' => $periodo,
            'docsdc' => $docsdc,
            'periodos' => $periodose
        );
    }

    /**
     * Lists all Docente entities por escuela y periodo
     *
     * @Route("/esc/{id}/{periodo}", name="docente_escuela")
     * @Method("GET")
     * @Template("Docente/porescuela.html.twig")
     */
    public function indexEscuelaAction($id, $periodo)
    {
        $em = $this->getDoctrine()->getManager();
        $escuela = $em->getRepository('AdminUnadBundle:Escuela')->findOneBy(array('id' => $id));
        $entities = $em->getRepository('AdminUnadBundle:Docente')->findBy(array('escuela' => $escuela, 'periodo' => $periodo));
        $total = count($entities);
        return array(
            'entities' => $entities,
            'total' => $total,
            'escuela' => $escuela,
            'periodo' => $periodo
        );
    }


    /**
     * Lists all Docente entities.
     *
     * @Route("/vinc/{id}", name="docente_vinculacion")
     * @Method("GET")
     * @Template("Docente/porvinculacion.html.twig")
     */
    public function indexVinculacionAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AdminUnadBundle:Docente')->findBy(array('vinculacion' => $id, 'periodo' => $this->container->getParameter('appmed.periodo')));
        // $periodo= $this->container->getParameter('appmed.periodo');
        // $entities = $em->getRepository('AdminUnadBundle:Docente')->porVinculacion($id,$periodo);

        $total = count($entities);
        return array(
            'entities' => $entities,
            'total' => $total,
            'id' => $id
        );
    }

    /**
     * Listado de docentes carrera por escuela.
     *
     * @Route("/dc", name="docente_dc")
     * @Method("GET")
     * @Template("AdminUnadBundle:Docente:dc.html.twig")
     */
    public function indexDcAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AdminUnadBundle:Docente')->findBy(array('vinculacion' => 'DC', 'periodo' => $this->container->getParameter('appmed.periodo')));
        $total = count($entities);
        return array(
            'entities' => $entities,
            'total' => $total,
        );
    }

    /**
     * Listado de docentes carrera por escuela.
     *
     * @Route("/dc", name="docente_dcescuela")
     * @Method("GET")
     * @Template("AdminUnadBundle:Docente:porescueladc.html.twig")
     */
    public function indexDcescuelaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $id = $em->getRepository('AdminUnadBundle:Escuela')->find($session->get('escuelaid'));
        $escuela = $em->getRepository('AdminUnadBundle:Escuela')->findOneBy(array('id' => $id));
        $entities = $em->getRepository('AdminUnadBundle:Docente')->findBy(array('escuela' => $escuela, 'vinculacion' => 'DC', 'periodo' => $this->container->getParameter('appmed.periodo')));
        $total = count($entities);
        return array(
            'entities' => $entities,
            'total' => $total,
            'escuela' => $escuela,
        );
    }

    /**
     * Listado de docentes carrera por zona.
     *
     * @Route("/zn", name="docente_dczona")
     * @Method("GET")
     * @Template("AdminUnadBundle:Docente:porzonadc.html.twig")
     */
    public function indexZonaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $zona = $em->getRepository('AdminUnadBundle:Zona')->find($session->get('zonaid'));
        $centro = $em->getRepository('AdminUnadBundle:Centro')->findBy(array('zona' => $zona));
        $entities = $em->getRepository('AdminUnadBundle:Docente')->findBy(array('centro' => $centro, 'vinculacion' => 'DC', 'periodo' => $this->container->getParameter('appmed.periodo')));
        $total = count($entities);
        return array(
            'entities' => $entities,
            'total' => $total
        );
    }

    /**
     * Creates a new Docente entity.
     *
     * @Route("/", name="docente_create")
     * @Method("POST")
     * @Template("AdminUnadBundle:Docente:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Docente();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('docente_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Docente entity.
     *
     * @param Docente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Docente $entity)
    {
        $form = $this->createForm(DocenteType::class, $entity, array(
            'action' => $this->generateUrl('docente_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Docente entity.
     *
     * @Route("/new", name="docente_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Docente();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Docente entity
     * @Route("/{id}", name="docente_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($id);
        $instrumentos = $em->getRepository('AdminMedBundle:Instrumento')->findAll();
        $this->get('session')->getFlashBag()->add('warning', 'El plazo para el proceso se extiende hasta el lunes 16 inclusive');
        $periodo = $em->getRepository('AdminMedBundle:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
            'instrumentos' => $instrumentos,
            'periodo' => $periodo
        );
    }


    /**
     * Finds and displays a Docente entity
     * @Route("/inicio/", name="docente_inicio")
     * @Method("GET")
     * @Template()
     */
    public function inicioAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $this->get('session')->getFlashBag()->add('success', 'Periodo de evaluación seleccionado: ' . $session->get('periodoe'));
        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));
        $instrumentos = $em->getRepository('AdminMedBundle:Instrumento')->findAll();
        $periodo = $em->getRepository('AdminMedBundle:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));

        if (!$entity) {
            throw $this->createNotFoundException('Error al buscarla entidad del docente.');
        }

        if ($entity->getVinculacion() == 'DOFE') {
            $red = $em->getRepository('AdminMedBundle:RedDofe')->findBy(array('docente' => $entity));
            return $this->render('AdminUnadBundle:Docente:iniciodofe.html.twig', array(
                'entity' => $entity,
                'instrumentos' => $instrumentos,
                'red' => $red,
                'periodo' => $periodo
            ));
        } else {
            return $this->render('AdminUnadBundle:Docente:show.html.twig', array(
                'entity' => $entity,
                'instrumentos' => $instrumentos,
                'periodo' => $periodo
            ));
        }
    }

    /**
     * Finds and displays a Docente entity
     * @Route("/{id}/info", name="docente_info")
     * @Method("GET")
     * @Template()
     */
    public function infoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($id);
        $periodo = $em->getRepository('AdminMedBundle:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docente entity.');
        }

        if ($entity->getVinculacion() == 'DOFE') {
            return $this->render('AdminUnadBundle:Docente:dofe.html.twig', array(
                'entity' => $entity,
            ));
        } else {
            return array(
                'entity' => $entity,
                'periodo' => $periodo
            );
        }
    }

    /**
     * Displays a form to edit an existing Docente entity.
     *
     * @Route("/{id}/edit", name="docente_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docente entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Docente entity.
     *
     * @param Docente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Docente $entity)
    {
        $form = $this->createForm(DocenteType::class, $entity, array(
            'action' => $this->generateUrl('docente_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Docente entity.
     *
     * @Route("/{id}", name="docente_update")
     * @Method("PUT")
     * @Template("AdminUnadBundle:Docente:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('docente_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Docente entity.
     *
     * @Route("/{id}", name="docente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminUnadBundle:Docente')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Docente entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('docente'));
    }

    /**
     * Creates a form to delete a Docente entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('docente_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * @Method("GET")
     * @Template()
     */
    public function coevaltutorAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));
        $ofertas = $em->getRepository('AdminMedBundle:Oferta')->findBy(array('director' => $entity));

        return array(
            'entity' => $entity,
            'ofertas' => $ofertas,
        );
    }

    /**
     * @Method("GET")
     * @Template()
     */
    public function coevaldirectorAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($session->get('docenteid'));

        return array(
            'entity' => $entity,
        );
    }

    /**
     * @Method("GET")
     * @Template()
     */
    public function coevalinfoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($id);
        return array(
            'entity' => $entity,
        );
    }

    /**
     * @Route("/final/{id}", name="docente_final")
     * @Method("GET")
     * @Template("AdminUnadBundle:Docente:final.html.twig")
     */
    public function finalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($id);

        if ($entity->getVinculacion() == 'De Carrera') {
            return $this->render('AdminUnadBundle:Docente:finaldc.html.twig', array(
                'docente' => $entity,
            ));
        } else if ($entity->getVinculacion() == 'DOFE') {
            $red = $em->getRepository('AdminMedBundle:RedDofe')->findBy(array('docente' => $entity));
            return $this->render('AdminUnadBundle:Docente:finaldofe.html.twig', array(
                'docente' => $entity,
                'red' => $red
            ));
        } else {
            return array(
                'docente' => $entity,
            );
        }
    }

    /**
     * @Route("/observ/{id}", name="docente_observ")
     * @Method("GET")
     * @Template("AdminUnadBundle:Docente:observ.html.twig")
     */
    public function observAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($id);

        $Form = $this->createForm(ObservType::class);

        return array(
            'docente' => $entity,
            'form' => $Form->createView(),
        );
    }

    /**
     * @Route("/observaciones/{id}", name="docente_observaciones")
     * @Method("PUT")
     * @Template("AdminUnadBundle:Docente:info.html.twig")
     */
    public function observacionesAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminUnadBundle:Docente')->find($id);
        $evaluacion = $em->getRepository('AdminMedBundle:evaluacion')->find($id);

        $Form = $this->createForm(ObservType::class);
        $Form->handleRequest($request);
        $evaluacion->setObservaciones($Form->get('observaciones')->getData());
        $em->persist($evaluacion);
        $em->flush();
        return $this->redirect($this->generateUrl('docente_info', array('id' => $id)));
    }

}
