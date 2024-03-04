<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Docente;
use AppBundle\Entity\Escuela;
use AppBundle\Entity\Instrumento;
use AppBundle\Entity\Evaluacion;
use AppBundle\Form\DocenteType;
use AppBundle\Form\ObservType;

/**
 * Docente controller
 * @Route("/unad/doc")
 */
class DocenteController extends Controller
{

    /**
     * Lists all Docente entities.
     *
     * @Route("/pe/{periodo}", name="docente", methods={"GET"})
     * @Template("Docente/porperiodo.html.twig")
     */
    public function indexAction($periodo)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Docente')->docentesPeriodo($periodo);
        return array(
            'entities' => $entities,
            'periodo' => $periodo
        );
    }

    /**
     * Home de Docentes
     * @Route("/home/{periodo}", name="docente_home", methods={"GET"})
     * @Template("Docente/home.html.twig")
     */
    public function homeAction($periodo)
    {
        $em = $this->getDoctrine()->getManager();
        $escuelas = $em->getRepository('AppBundle:Escuela')->findAll();
        $periodose = $em->getRepository('AppBundle:Periodoe')->findby(array(), array('id' => 'DESC'), 10);
        $docsdc = $em->getRepository('AppBundle:Docente')->totalEscuelas($periodo);
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
     * @Route("/esc/{id}/{periodo}", name="docente_escuela", methods={"GET"})
     * @Template("Docente/porescuela.html.twig")
     */
    public function indexEscuelaAction($id, $periodo)
    {
        $em = $this->getDoctrine()->getManager();
        $escuela = $em->getRepository('AppBundle:Escuela')->findOneBy(array('id' => $id));
        $periodoe = $em->getRepository('AppBundle:Periodoe')->findOneBy(array('id' => $periodo));
        $entities = $em->getRepository('AppBundle:Docente')->resultadosEscuelaPeriodo($escuela, $periodo);

//        $entities = $em->getRepository('AppBundle:Docente')->findBy(array('escuela' => $escuela, 'periodo' => $periodo));
        $total = count($entities);
        return array(
            'entities' => $entities,
            'total' => $total,
            'escuela' => $escuela,
            'periodo' => $periodoe
        );
    }


    /**
     * Lists all Docente entities por escuela y periodo
     *
     * @Route("/esc/{id}/{periodo}/csv", name="docente_escuela_csv", methods={"GET"})
     * @Template("Docente/porescuela.html.twig")
     */
    public function indexResultadosCsvAction($id, $periodo)
    {
        $em = $this->getDoctrine()->getManager();
        $escuela = $em->getRepository('AppBundle:Escuela')->findOneBy(array('id' => $id));
        $resultados = $em->getRepository('AppBundle:Docente')->resultadosEscuelaPeriodo($escuela, $periodo);
        $response = new Response();
        $responseString = $this->array2csv($resultados);
        $periodoe = $em->getRepository('AppBundle:Periodoe')->findOneBy(array('id' => $periodo));
        $response->headers->set('Content-type', 'application/vnd.ms-excel');
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-Disposition', 'attachment; filename="resultados-' . $escuela->getSigla() .'-'.$periodoe->getYear() .'_'. $periodoe->getObservaciones() . '.xls";');
        $response->sendHeaders();
        $response->setContent($responseString);
        return $response;
    }

    function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputs($df, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        fputcsv($df, array_keys(reset($array)),";");
        foreach ($array as $row) {
            fputcsv($df, $row, ";");
        }
        fclose($df);
        return ob_get_clean();
    }


    /**
     * Lists all Docente entities.
     *
     * @Route("/vinc/{id}", name="docente_vinculacion", methods={"GET"})
     * @Template("Docente/porvinculacion.html.twig")
     */
    public function indexVinculacionAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Docente')->findBy(array('vinculacion' => $id, 'periodo' => $this->container->getParameter('appmed.periodo')));

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
     * @Route("/dc", name="docente_dc", methods={"GET"})
     * @Template("Docente/dc.html.twig")
     */
    public function indexDcAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Docente')->findBy(array('vinculacion' => 'DC', 'periodo' => $this->container->getParameter('appmed.periodo')));
        $total = count($entities);
        return array(
            'entities' => $entities,
            'total' => $total,
        );
    }

    /**
     * Listado de docentes carrera por escuela.
     *
     * @Route("/dc", name="docente_dcescuela", methods={"GET"})
     * @Template("Docente/porescueladc.html.twig")
     */
    public function indexDcescuelaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $id = $em->getRepository('AppBundle:Escuela')->find($session->get('escuelaid'));
        $escuela = $em->getRepository('AppBundle:Escuela')->findOneBy(array('id' => $id));
        $entities = $em->getRepository('AppBundle:Docente')->findBy(array('escuela' => $escuela, 'vinculacion' => 'DC', 'periodo' => $this->container->getParameter('appmed.periodo')));
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
     * @Route("/zn", name="docente_dczona", methods={"GET"})
     * @Template("Docente/porzonadc.html.twig")
     */
    public function indexZonaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $zona = $em->getRepository('AppBundle:Zona')->find($session->get('zonaid'));
        $centro = $em->getRepository('AppBundle:Centro')->findBy(array('zona' => $zona));
        $entities = $em->getRepository('AppBundle:Docente')->findBy(array('centro' => $centro, 'vinculacion' => 'DC', 'periodo' => $this->container->getParameter('appmed.periodo')));
        $total = count($entities);
        return array(
            'entities' => $entities,
            'total' => $total
        );
    }

    /**
     * Creates a new Docente entity.
     *
     * @Route("/", name="docente_create",  methods={"POST"})
     * @Template("Docente/new.html.twig")
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
     * @Route("/new", name="docente_new", methods={"GET"})
     * @Template("Docente/new.html.twig")
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
     * @Route("/{id}", name="docente_show", methods={"GET"})
     * @Template("Docente/show.html.twig")
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AppBundle:Docente')->find($id);
        $instrumentos = $em->getRepository('AppBundle:Instrumento')->findAll();
        //$this->get('session')->getFlashBag()->add('warning', 'El plazo para el proceso se extiende hasta el lunes 16 inclusive');
        $periodo = $em->getRepository('AppBundle:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));

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
     * @Route("/inicio/", name="docente_inicio", methods={"GET"})
     * @Template("Docente/inicio.html.twig")
     */
    public function inicioAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $docenteid = $session->get('docenteid');
        if ($docenteid == null) {
            return $this->redirect($this->generateUrl('home_user_inicio'));
        }
        $entity = $em->getRepository('AppBundle:Docente')->find($docenteid);
        $instrumentos = $em->getRepository('AppBundle:Instrumento')->findAll();
        $periodo = $em->getRepository('AppBundle:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));
        $this->get('session')->getFlashBag()->add('success', 'Periodo de evaluación seleccionado: ' . $periodo->getObservaciones());


        if (!$entity) {
            throw $this->createNotFoundException('Error al buscarla entidad del docente.');
        }

        if ($entity->getVinculacion() == 'DOFE') {
            $red = $em->getRepository('AppBundle:RedDofe')->findBy(array('docente' => $entity));
            return $this->render('Docente/iniciodofe.html.twig', array(
                'entity' => $entity,
                'instrumentos' => $instrumentos,
                'red' => $red,
                'periodo' => $periodo
            ));
        } else if ($entity->getVinculacion() == 'De Carrera') {
            return $this->render('Docente/inicio-dc.html.twig', array(
                'entity' => $entity,
                'instrumentos' => $instrumentos,
                'periodo' => $periodo
            ));
        } else {
            return $this->render('Docente/show.html.twig', array(
                'entity' => $entity,
                'instrumentos' => $instrumentos,
                'periodo' => $periodo
            ));
        }
    }

    /**
     * Finds and displays a Docente entity
     * @Route("/{id}/info", name="docente_info", methods={"GET"})
     * @Template("Docente/info.html.twig")
     */
    public function infoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AppBundle:Docente')->find($id);
        $periodo = $em->getRepository('AppBundle:Periodoe')->findOneBy(array('id' => $entity->getPeriodo()));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docente entity.');
        }

        if ($entity->getVinculacion() == 'DOFE') {
            return $this->render('Docente/dofe.html.twig', array(
                'entity' => $entity,
                'periodo' => $periodo
            ));
        } else {
            return array(
                'entity' => $entity,
                'periodo' => $periodo
            );
        }
    }


    /**
     * Finds and displays a Docente entity
     * @Route("/{id}/final", name="docente_final_anual", methods={"GET"})
     * @Template("Docente/finalanual.html.twig")
     */
    public function finalAnual(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('AppBundle:Docente')->find($id);
        $periodo = $em->getRepository('AppBundle:Periodoe')->findOneBy(array('id' => $entity->getPeriodo()));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docente entity.');
        }

        if ($entity->getVinculacion() == 'DOFE') {
            return $this->render('Docente/dofe.html.twig', array(
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
     * @Route("/{id}/edit", name="docente_edit", methods={"GET"})
     * @Template("Docente/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Docente')->find($id);

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
     * @Route("/{id}", name="docente_update", methods={"PUT"})
     * @Template("Docente/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Docente')->find($id);

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
     * @Route("/{id}", name="docente_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Docente')->find($id);

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
     * @Template("Docente/coevaltutor.html.twig")
     */
    public function coevaltutorAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $programas = $em->getRepository('AppBundle:Programa')->findBy(array('lider' => $user));

        $entity = $em->getRepository('AppBundle:Docente')->find($session->get('docenteid'));
        $ofertas = $em->getRepository('AppBundle:Oferta')->findBy(array('director' => $entity));

        return array(
            'entity' => $entity,
            'ofertas' => $ofertas,
        );
    }


    /**
     * @Template("Docente/coevalinfo.html.twig")
     */
    public function coevalinfoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Docente')->find($id);
        return array(
            'entity' => $entity,
        );
    }

    /**
     * @Route("/final/{id}", name="docente_final", methods={"GET"})
     * @Template("Docente/final.html.twig")
     */
    public function finalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Docente')->find($id);
        $periodo = $em->getRepository('AppBundle:Periodoe')->findOneBy(array('id' => $entity->getPeriodo()));
        $parciales = $em->getRepository('AppBundle:Docente')->evalAnual($periodo->getYear(), $entity->getUser()->getId());
        if ($entity->getVinculacion() == 'De Carrera') {
            return $this->render('Docente/finaldc.html.twig', array(
                'docente' => $entity,
                'periodo' => $periodo
            ));
        } else if ($entity->getVinculacion() == 'DOFE') {
            $red = $em->getRepository('AppBundle:RedDofe')->findBy(array('docente' => $entity));
            return $this->render('Docente/finaldofe.html.twig', array(
                'docente' => $entity,
                'red' => $red
            ));
        } else if($periodo->getType() == "a"){
            return $this->render('Docente/finalanual.html.twig', array(
                'docente' => $entity,
                'periodo' => $periodo,
                'parciales' => $parciales
            ));
        }
        else {
            return array(
                'docente' => $entity,
                'periodo' => $periodo
            );
        }
    }

    /**
     * @Route("/observ/{id}", name="docente_observ", methods={"GET"})
     * @Template("Docente/observ.html.twig")
     */
    public function observAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Docente')->find($id);

        $Form = $this->createForm(ObservType::class);

        return array(
            'docente' => $entity,
            'form' => $Form->createView(),
        );
    }

    /**
     * @Route("/observaciones/{id}", name="docente_observaciones", methods={"PUT"})
     * @Template("Docente/info.html.twig")
     */
    public function observacionesAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Docente')->find($id);
        $evaluacion = $em->getRepository('AppBundle:evaluacion')->find($id);

        $Form = $this->createForm(ObservType::class);
        $Form->handleRequest($request);
        $evaluacion->setObservaciones($Form->get('observaciones')->getData());
        $em->persist($evaluacion);
        $em->flush();
        return $this->redirect($this->generateUrl('docente_info', array('id' => $id)));
    }

}
