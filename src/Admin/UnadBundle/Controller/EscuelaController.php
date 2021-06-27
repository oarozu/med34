<?php

namespace Admin\UnadBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Admin\UnadBundle\Entity\Escuela;
use Admin\UnadBundle\Form\EscuelaType;

/**
 * Escuela controller.
 *
 * @Route("/unad/escuela")
 */
class EscuelaController extends Controller
{

    /**
     * Lists all Escuela entities.
     *
     * @Route("/", name="escuela")
     * @Method("GET")
     * @Template("Escuela/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminUnadBundle:Escuela')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Escuela entity.
     *
     * @Route("/", name="escuela_create")
     * @Method("POST")
     * @Template("Escuela/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Escuela();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
 
        $em = $this->getDoctrine()->getManager();
        $decano = $em->getRepository('AdminUserBundle:User')->find($form["decano"]->getData());
        $entity->setDecano($decano);
        $secretaria = $em->getRepository('AdminUserBundle:User')->find($form["secretaria"]->getData());
        $entity->setSecretaria($secretaria);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('escuela_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
        $form = $this->createForm( EscuelaType::class, $entity, array(
            'action' => $this->generateUrl('escuela_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Escuela entity.
     *
     * @Route("/new", name="escuela_new")
     * @Method("GET")
     * @Template("Escuela/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Escuela();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Escuela entity.
     *
     * @Route("/{id}", name="escuela_show")
     * @Method("GET")
     * @Template("Escuela/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Escuela')->find($id);
        $terna = $em->getRepository('AdminUnadBundle:Terna')->findBy(array('escuela' => $entity, 'periodo' => $this->container->getParameter('appmed.periodo')));


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'terna'       => $terna,
            'delete_form' => $deleteForm->createView(),
        );
    }
    
     /**
     * Finds and displays a Escuela entity.
     *
     * @Route("/mi/info", name="escuela_info")
     * @Method("GET")
     * @Template("Escuela/info.html.twig")
     */
    public function infoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();

        $escuela = $em->getRepository('AdminUnadBundle:Escuela')->find($session->get('escuelaid'));
        $periodose = $em->getRepository('AdminMedBundle:Periodoe')->findby(array(),array('id' => 'DESC'));
        $programas = $em->getRepository('AdminUnadBundle:Programa')->findBy(array('escuela' => $escuela),array('nivel' => 'DESC'));
        
        $ofertado = $em->getRepository('AdminUnadBundle:ProgramaPeriodo')->findby(array('programa' => $programas, 'periodo' => $this->container->getParameter('appmed.periodo')));

        
        if (!$escuela) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }
        return array(
        'entity'      => $escuela,
        'ofertado'   => $ofertado,
        'periodos'    => $periodose
        );
    }
    
    /**
     * Displays a form to edit an existing Escuela entity.
     *
     * @Route("/{id}/edit", name="escuela_edit")
     * @Method("GET")
     * @Template("Escuela/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Escuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm["decano"]->setData($entity->getDecano()->getId());
        $editForm["secretaria"]->setData($entity->getSecretaria()->getId());
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
        $form = $this->createForm( EscuelaType::class, $entity, array(
            'action' => $this->generateUrl('escuela_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Escuela entity.
     *
     * @Route("/{id}", name="escuela_update")
     * @Method("PUT")
     * @Template("Escuela/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Escuela')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        $decano = $em->getRepository('AdminUserBundle:User')->find($editForm["decano"]->getData());
        $entity->setDecano($decano);
        $secretaria = $em->getRepository('AdminUserBundle:User')->find($editForm["secretaria"]->getData());
        $entity->setSecretaria($secretaria);
        
        if ($editForm->isValid() && $secretaria && $decano) {
            $em->flush();

            return $this->redirect($this->generateUrl('escuela_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Escuela entity.
     *
     * @Route("/{id}", name="escuela_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminUnadBundle:Escuela')->find($id);

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
            ->getForm()
        ;
    }
    
     /**
     * Finds and displays a Escuela entity.
     *
     * @Method("GET")
     * @Template("Escuela/coevallider.html.twig")
     */
    public function coevalliderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $escuela = $em->getRepository('AdminUnadBundle:Escuela')->find($session->get('escuelaid'));
        if (!$escuela) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }
        return array(
        'entity'      => $escuela,
        );
    }
    
    
    
    
     /**
     * Lista la evaluacion de estudiantes
     * @Route("/mi/heteroeval", name="escuela_heteroeval")
     * @Method("GET")
     * @Template("Escuela/heteroeval.html.twig")
     */
    public function heteroevalAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $escuela = $em->getRepository('AdminUnadBundle:Escuela')->find($session->get('escuelaid'));
        $docentes = $em->getRepository('AdminUnadBundle:Docente')->findby(array( 'escuela' => $session->get('escuelaid')));
        if (!$escuela) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }
        return array(
        'escuela'      => $escuela,
        'docentes'    => $docentes,
        );
    }
    
    
      /**
     * @Route("/mi/resultados", name="escuela_resultados")
     * @Method("GET")
     * @Template("Escuela/resultados.html.twig")
     */
    public function resultadosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $escuela = $em->getRepository('AdminUnadBundle:Escuela')->find($session->get('escuelaid'));
        if (!$escuela) {
            throw $this->createNotFoundException('Unable to find Escuela entity.');
        }
        return array(
        'escuela'      => $escuela,
        );
    }
}
