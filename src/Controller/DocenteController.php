<?php

namespace App\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Docente;
use App\Entity\Escuela;
use App\Entity\Instrumento;
use App\Entity\Evaluacion;
use App\Form\DocenteType;
use App\Form\ObservType;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Docente controller
 * @Route("/unad/doc")
 */
class DocenteController extends AbstractController
{

    /**
     * Lists all Docente entities.
     *
     * @Route("/pe/{periodo}", name="docente", methods={"GET"})
     */
    public function indexAction($periodo):Response
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('App:Docente')->docentesPeriodo($periodo);
        return $this->render('Docente/porperiodo.html.twig', array(
            'entities' => $entities,
            'periodo' => $periodo
        ));
    }

    /**
     * Home de Docentes
     * @Route("/home/{periodo}", name="docente_home", methods={"GET"})
     */
    public function homeAction($periodo):Response
    {
        $em = $this->getDoctrine()->getManager();
        $escuelas = $em->getRepository('App:Escuela')->findAll();
        $periodose = $em->getRepository('App:Periodoe')->findby(array(), array('id' => 'DESC'), 10);
        $docsdc = $em->getRepository('App:Docente')->totalEscuelas($periodo);
        return $this->render('Docente/home.html.twig', array(
            'escuelas' => $escuelas,
            'periodo' => $periodo,
            'docsdc' => $docsdc,
            'periodos' => $periodose
        ));
    }

    /**
     * Lists all Docente entities por escuela y periodo
     *
     * @Route("/esc/{id}/{periodo}", name="docente_escuela", methods={"GET"})
     */
    public function indexEscuelaAction($id, $periodo):Response
    {
        $em = $this->getDoctrine()->getManager();
        $escuela = $em->getRepository('App:Escuela')->findOneBy(array('id' => $id));
        $periodoe = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $periodo));
        $entities = $em->getRepository('App:Docente')->resultadosEscuelaPeriodo($escuela, $periodo);

        $total = count($entities);
        return $this->render('Docente/porescuela.html.twig', array(
            'entities' => $entities,
            'total' => $total,
            'escuela' => $escuela,
            'periodo' => $periodoe
        ));
    }


    /**
     * Lists all Docente entities por escuela y periodo
     *
     * @Route("/esc/{id}/{periodo}/csv", name="docente_escuela_csv", methods={"GET"})
     */
    public function indexResultadosCsvAction($id, $periodo):Response
    {
        $em = $this->getDoctrine()->getManager();
        $escuela = $em->getRepository('App:Escuela')->findOneBy(array('id' => $id));
        $resultados = $em->getRepository('App:Docente')->resultadosEscuelaPeriodo($escuela, $periodo);
        $response = new Response();
        $responseString = $this->array2csv($resultados);
        $periodoe = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $periodo));
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
     #   fputs($df, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
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
     */
    public function indexVinculacionAction($id):Response
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('App:Docente')->findBy(array('vinculacion' => $id, 'periodo' => $this->getParameter('appmed.periodo')));

        $total = count($entities);
        return $this->render('Docente/porvinculacion.html.twig', array(
            'entities' => $entities,
            'total' => $total,
            'id' => $id
        ));
    }

    /**
     * Listado de docentes carrera por escuela.
     *
     * @Route("/dc", name="docente_dc", methods={"GET"})
     */
    public function indexDcAction():Response
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('App:Docente')->findBy(array('vinculacion' => 'DC', 'periodo' => $this->getParameter('appmed.periodo')));
        $total = count($entities);
        return $this->render('Docente/dc.html.twig', array(
            'entities' => $entities,
            'total' => $total,
        ));
    }

    /**
     * Listado de docentes carrera por escuela.
     *
     * @Route("/dc", name="docente_dcescuela", methods={"GET"})
     */
    public function indexDcescuelaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $id = $em->getRepository('App:Escuela')->find($session->get('escuelaid'));
        $escuela = $em->getRepository('App:Escuela')->findOneBy(array('id' => $id));
        $entities = $em->getRepository('App:Docente')->findBy(array('escuela' => $escuela, 'vinculacion' => 'DC', 'periodo' => $this->getParameter('appmed.periodo')));
        $total = count($entities);
        return $this->render('Docente/porescueladc.html.twig', array(
            'entities' => $entities,
            'total' => $total,
            'escuela' => $escuela,
        ));
    }

    /**
     * Listado de docentes carrera por zona.
     *
     * @Route("/zn", name="docente_dczona", methods={"GET"})
     */
    public function indexZonaAction(Request $request):Response
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $zona = $em->getRepository('App:Zona')->find($session->get('zonaid'));
        $centro = $em->getRepository('App:Centro')->findBy(array('zona' => $zona));
        $entities = $em->getRepository('App:Docente')->findBy(array('centro' => $centro, 'vinculacion' => 'DC', 'periodo' => $this->getParameter('appmed.periodo')));
        $total = count($entities);
        return $this->render('Docente/porzonadc.html.twig', array(
            'entities' => $entities,
            'total' => $total
        ));
    }

    /**
     * Creates a new Docente entity.
     *
     * @Route("/", name="docente_create",  methods={"POST"})
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

        return $this->render('Docente/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Docente entity.
     *
     * @param Docente $entity The entity
     *
     */
    private function createCreateForm(Docente $entity):FormInterface
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
     */
    public function newAction()
    {
        $entity = new Docente();
        $form = $this->createCreateForm($entity);

        return $this->render('Docente/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Docente entity
     * @Route("/{id}/show", name="docente_show", methods={"GET"})
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('App:Docente')->find($id);
        $instrumentos = $em->getRepository('App:Instrumento')->findAll();
        //$this->get('session')->getFlashBag()->add('warning', 'El plazo para el proceso se extiende hasta el lunes 16 inclusive');
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Docente/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
            'instrumentos' => $instrumentos,
            'periodo' => $periodo
        ));
    }


    /**
     * Finds and displays a Docente entity
     * @Route("/inicio", name="docente_inicio", methods={"GET"})
     */
    public function inicioAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $docenteid = $session->get('docenteid');
        if ($docenteid == null) {
            return $this->redirect($this->generateUrl('home_user_inicio'));
        }
        $entity = $em->getRepository('App:Docente')->find($docenteid);
        $instrumentos = $em->getRepository('App:Instrumento')->findAll();
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $session->get('periodoe')));
        $this->get('session')->getFlashBag()->add('success', 'Periodo de evaluación seleccionado: ' . $periodo->getObservaciones());


        if (!$entity) {
            throw $this->createNotFoundException('Error al buscarla entidad del docente.');
        }

        if ($entity->getVinculacion() == 'DOFE') {
            $red = $em->getRepository('App:RedDofe')->findBy(array('docente' => $entity));
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
     */
    public function infoAction(Request $request, $id):Response
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:Docente')->find($id);
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $entity->getPeriodo()));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docente entity.');
        }

        if ($entity->getVinculacion() == 'DOFE') {
            return $this->render('Docente/dofe.html.twig', array(
                'entity' => $entity,
                'periodo' => $periodo
            ));
        } else {
            return $this->render('Docente/info.html.twig', array(
                'entity' => $entity,
                'periodo' => $periodo
            ));
        }
    }


    /**
     * Finds and displays a Docente entity
     * @Route("/{id}/final", name="docente_final_anual", methods={"GET"})
     */
    public function finalAnual(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $entity = $em->getRepository('App:Docente')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docente entity.');
        }

        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $entity->getPeriodo()));

        if ($entity->getVinculacion() == 'DOFE') {
            return $this->render('Docente/dofe.html.twig', array(
                'entity' => $entity,
            ));
        } else {
            return $this->render('Docente/finalanual.html.twig', array(
                'entity' => $entity,
                'periodo' => $periodo
            ));
        }
    }

    /**
     * Displays a form to edit an existing Docente entity.
     *
     * @Route("/{id}/edit", name="docente_edit", methods={"GET"})
     */
    public function editAction($id, EntityManagerInterface $em)
    {
        $entity  = $em->getRepository('App\Entity\Docente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Docente entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Docente/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Docente entity.
     *
     * @param Docente $entity The entity
     *
     */
    private function createEditForm(Docente $entity):FormInterface
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
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('App\Entity\Docente')->find($id);

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

        return $this->render('Docente/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
            $entity = $em->getRepository('App:Docente')->find($id);

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
     */
    private function createDeleteForm($id):FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('docente_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm();
    }

    /**
     */
    public function coevaltutorAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $programas = $em->getRepository('App:Programa')->findBy(array('lider' => $user));

        $entity = $em->getRepository('App:Docente')->find($session->get('docenteid'));
        $ofertas = $em->getRepository('App:Oferta')->findBy(array('director' => $entity));

        return $this->render('Docente/coevaltutor.html.twig', array(
            'entity' => $entity,
            'ofertas' => $ofertas,
        ));
    }


    /**
     */
    public function coevalinfoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:Docente')->find($id);
        return $this->render('Docente/coevalinfo.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * @Route("/final/{id}", name="docente_final", methods={"GET"})
     */
    public function finalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:Docente')->find($id);
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $entity->getPeriodo()));
        $parciales = $em->getRepository('App:Docente')->evalAnual($periodo->getYear(), $entity->getUser()->getId());
        if ($entity->getVinculacion() == 'De Carrera') {
            return $this->render('Docente/finaldc.html.twig', array(
                'docente' => $entity,
                'periodo' => $periodo
            ));
        } else if ($entity->getVinculacion() == 'DOFE') {
            $red = $em->getRepository('App:RedDofe')->findBy(array('docente' => $entity));
            return $this->render('Docente/finaldofe.html.twig', array(
                'docente' => $entity,
                'red' => $red
            ));
        } else if($periodo->getType() == "a"){
            return $this->render('Docente/final.html.twig', array(
                'docente' => $entity,
                'periodo' => $periodo,
                'parciales' => $parciales
            ));
        }
        else {
            return $this->render('Docente/porperiodo.html.twig', array(
                'docente' => $entity,
                'periodo' => $periodo
            ));
        }
    }

    /**
     * @Route("/observ/{id}", name="docente_observ", methods={"GET"})
     */
    public function observAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:Docente')->find($id);

        $Form = $this->createForm(ObservType::class);

        return $this->render('Docente/observ.html.twig', array(
            'docente' => $entity,
            'form' => $Form->createView(),
        ));
    }

    /**
     * @Route("/observaciones/{id}", name="docente_observaciones", methods={"PUT"})
     */
    public function observacionesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:Docente')->find($id);
        $evaluacion = $em->getRepository('App:evaluacion')->find($id);

        $Form = $this->createForm(ObservType::class);
        $Form->handleRequest($request);
        $evaluacion->setObservaciones($Form->get('observaciones')->getData());
        $em->persist($evaluacion);
        $em->flush();
        return $this->redirect($this->generateUrl('docente_info', array('id' => $id)));
    }
}
