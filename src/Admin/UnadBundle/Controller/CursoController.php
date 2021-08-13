<?php

namespace Admin\UnadBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\UnadBundle\Entity\Curso;
use Admin\MedBundle\Entity\Oferta;
use Admin\MedBundle\Entity\Cedula;
use Admin\MedBundle\Entity\Tutor;
use Admin\UnadBundle\Form\CursoType;
use Admin\MedBundle\Form\ofertaType;
use Admin\MedBundle\Form\CedulaType;
use Admin\UnadBundle\Form\CursoprogType;

/**
 * Curso controller.
 *
 * @Route("/unad/curso")
 */
class CursoController extends Controller
{

    /**
     * Lists all Curso entities.
     *
     * @Route("/", name="curso")
     * @Method("GET")
     * @Template("Curso/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AdminUnadBundle:Curso')->findAll();
        return array(
            'entities' => $entities,
        );
    }
    
     /**
     * Lists all Curso entities.
     *
     * @Route("/pe/{sigla}", name="curso_escuela")
     * @Method("GET")
     * @Template("Curso/index.html.twig")
     * 
     */
    public function porescuelaAction($sigla)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AdminUnadBundle:Curso')->findBy(array('escuela' => $sigla));

        return array(
            'entities' => $entities,
        );
    }
    
    
    /**
     * Creates a new Curso entity.
     *
     * @Route("/", name="curso_create")
     * @Method("POST")
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
            'form'   => $form->createView(),
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
     * @Route("/new", name="curso_new")
     * @Method("GET")
     * @Template("Curso/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Curso();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Curso entity
     * @Route("/{id}", name="curso_show")
     * @Method("GET")
     * @Template("Curso/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Curso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }
        
        
       $periodos = $em->getRepository('AdminMedBundle:Periodoa')->findby(array('periodoe' => $this->container->getParameter('appmed.periodo')));
       $cursooferta = $em->getRepository('AdminMedBundle:Oferta')->findby(array('periodo' => $periodos, 'curso' => $entity));
        
        
       $datos = new \Admin\MedBundle\Entity\OfertaDatos();     
       $Form = $this->createForm(ofertaType::class, $datos, array(
            'action' => $this->generateUrl('oferta_curso', array('id' => $entity->getId())),
            'method' => 'GET',
        ));
        
        return array(
            'entity'      => $entity,
            'cursooferta'      =>$cursooferta,
            'form'      => $Form->createView(),
        );
    }
    
     /**
     * Docentes de un curso por oferta
     * @Route("/{id}/oferta", name="oferta")
     * @Method("GET")
     * @Template("Curso/oferta.html.twig")
     */
    
    public function ofertaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminMedBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }
       $cedula = new Cedula();     
       $Form = $this->createForm(CedulaType::class, $cedula, array(
            'action' => $this->generateUrl('oferta_tutor', array('id' => $entity->getId())),
            'method' => 'POST',
        ));  
        return array(
            'entity'      => $entity,
            'cedula_form'   => $Form->createView(),     
        );
    }
    
  
     /**
     * Finds and displays a Curso entity
     * @Route("/{id}/addoferta", name="oferta_curso")
     * @Method("POST")
     * @Template()
     */
    public function ofertaCursoAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $curso = $em->getRepository('AdminUnadBundle:Curso')->find($id);
      $oferta = new Oferta();
      $datos = new \Admin\MedBundle\Entity\OfertaDatos();
      $Form = $this->createForm(ofertaType::class, $datos);
      $Form->handleRequest($request);
      $numeroced = $Form->get('cedula')->getData();
      
      $usuario = $em->getRepository('AdminUserBundle:User')->find($numeroced);       
       if (!$usuario) {
       $this->get('session')->getFlashBag()->add('error', 'Cédula no encontrada');          
       return $this->redirect($this->generateUrl('curso_show', array('id' => $id)));          
       }
       
      $docente = $em->getRepository('AdminUnadBundle:Docente')->findOneBy(array('user' => $usuario, 'periodo' => $this->container->getParameter('appmed.periodo')));
          
      if (!$docente) {
      $this->get('session')->getFlashBag()->add('error', 'El número no corresponde a un docente');          
      return $this->redirect($this->generateUrl('curso_show', array('id' => $id)));          
      }
      
      $oferta->setCurso($curso);
      $oferta->setDirector($docente);
      $oferta->setPeriodo($Form->get('periodo')->getData());
      if($Form->get('periodo')->getData()=='16-04'){
      $oferta->setId($curso->getId()+10000000);    
      }
      else{
      $oferta->setId($curso->getId()+20000000);      
      }
      
     
      try{
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
     * @Route("/{id}/tutor", name="oferta_tutor")
     * @Method("POST")
     * @Template()
     */
    public function ofertaTutorAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $oferta = $em->getRepository('AdminMedBundle:Oferta')->find($id);
      $data = new Cedula();
      $form = $this->createForm(CedulaType::class, $data);
      $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }
      $session = $request->getSession();
      
      if($oferta->getDirector()->getId() != $session->get('docenteid') || $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
      $this->get('session')->getFlashBag()->add('error', 'No permitido no es director');
      return $this->redirect($this->generateUrl('oferta', array('id' => $id)));                     
       }
      
      $usuario = $em->getRepository('AdminUserBundle:User')->find($data->getCedula());
       if (!$usuario) {
       $this->get('session')->getFlashBag()->add('error', 'Cédula no encontrada');          
       return $this->redirect($this->generateUrl('oferta', array('id' => $id)));          
       }
   
      $docente = $em->getRepository('AdminUnadBundle:Docente')->findOneBy(array('user' => $usuario, 'periodo' => $this->container->getParameter('appmed.periodo')));
          
      if (!$docente) {
      $this->get('session')->getFlashBag()->add('error', 'El número no corresponde a un docente');          
      return $this->redirect($this->generateUrl('oferta', array('id' => $id)));          
      }
      
       if($docente->getId() == $oferta->getDirector()->getId()){
       $this->get('session')->getFlashBag()->add('warning', 'No es necesario que el director se agregue como tutor');          
       return $this->redirect($this->generateUrl('oferta', array('id' => $id)));      
       }
      
      $tutor = new Tutor();
      $tutor->setOferta($oferta);
      $tutor->setDocente($docente);
      try{
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
     * @Route("/{id}/modal", name="curso_modal")
     * @Method("GET")
     * @Template("Curso/modal.html.twig")
     */
    public function modalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminMedBundle:Oferta')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }
        
        $cedula = new Cedula();     
       $Form = $this->createForm(CedulaType::class, $cedula, array(
            'action' => $this->generateUrl('oferta_tutor', array('id' => $id)),
            'method' => 'GET',
        ));
        return array(
            'entity'      => $entity,
            'cedula_form'   => $Form->createView(),   
        );
    }

    /**
     * Displays a form to edit an existing Curso entity.
     *
     * @Route("/{id}/edit", name="curso_edit")
     * @Method("GET")
     * @Template("Curso/edit.html.twig")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Curso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }
        
        $editForm = $this->createEditarForm($entity, $request);

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
     /**
     * Displays a form to edit an existing Curso entity.
     * @Route("/{id}/ofertaedit", name="oferta_edit")
     * @Method("GET")
     * @Template("Curso/ofertaedit.html.twig")
     */
    public function ofertaeditAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminMedBundle:Oferta')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('NO se encontro oferta');
        }
       $cedula = new Cedula();     
       $Form = $this->createForm(CedulaType::class, $cedula, array(
            'action' => $this->generateUrl('oferta_update', array('id' => $entity->getId())),
            'method' => 'GET',
        ));  
        return array(
            'entity'      => $entity,
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
    private function createEditarForm(Curso $entity, Request $request)
    {
        $session = $request->getSession();
        $escuelaid = $session->get('escuelaid');
        $form = $this->createForm(CursoprogType::class, $entity, array(
            'action' => $this->generateUrl('curso_update', array('id' => $entity->getId())),
            'method' => 'PUT',
             )
             );
        $form->add('submit', SubmitType::class, array('label' => 'Actualizar'));
        return $form;
    
   }
    
     /**
     * Edits an existing Curso entity.
     * @Route("/{id}/ofertaupdate", name="oferta_update")
     * @Method("GET")
     * @Template("Curso/ofertaedit.html.twig")
     */
    public function ofertaupdateAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $oferta = $em->getRepository('AdminMedBundle:Oferta')->find($id);
      $cedula = new Cedula();     
      $Form = $this->createForm(CedulaType::class, $cedula, array(
      'action' => $this->generateUrl('oferta_update', array('id' => $id)),
      'method' => 'GET',
      )); 
      $Form->handleRequest($request);
      
      $ncedula = $Form->get('cedula')->getData();
      $user = $em->getRepository('AdminUserBundle:User')->find($ncedula);
      $docente = $em->getRepository('AdminUnadBundle:Docente')->findOneBy(array ('user' => $user, 'periodo' => $this->container->getParameter('appmed.periodo') ));      
      if (!$docente) {
      $this->get('session')->getFlashBag()->add('error', 'El número no corresponde a un docente');          
      return $this->redirect($this->generateUrl('oferta_edit', array('id' => $id)));          
      }
      $oferta->setDirector($docente);
      $em->flush();
       $this->get('session')->getFlashBag()->add('success', 'Se actualizo el director'); 
      return $this->redirect($this->generateUrl('oferta', array('id' => $id)));
    }
    
    /**
     * Edits an existing Curso entity.
     *
     * @Route("/{id}", name="curso_update")
     * @Method("PUT")
     * @Template("Curso/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUnadBundle:Curso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Curso entity.');
        }
        $session = $request->getSession();
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditarForm($entity, $request);
        $editForm->handleRequest($request);
        
        $director = $em->getRepository('AdminUnadBundle:Docente')->find($editForm->get('director')->getData());
        $entity->setDirector($director);
       
        if ($editForm->isValid()){
            $em->flush();

            return $this->redirect($this->generateUrl('curso_escuela', array('sigla' => $entity->getEscuela())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    
    /**
     * Deletes a Curso entity.
     *
     * @Route("/{id}", name="curso_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminUnadBundle:Curso')->find($id);

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
            ->getForm()
        ;
    }
    
     /**
     * Borrar Tutor.
     *
     * @Route("/tutor/{id}/del", name="tutor_delete")
     * @Method("GET")
     */
    public function borrartutorAction(Request $request, $id)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:Tutor')->find($id);
            $oferta = $entity->getOferta();
            $director = $oferta->getDirector();
            $session = $request->getSession();
            
            if($director->getId() == $session->get('docenteid') || $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
                try{
                $em->remove($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Se borro el docente del curso'); 
                } catch (\Doctrine\DBAL\DBALException $e) {
                $this->get('session')->getFlashBag()->add('warning', 'El tutor no se puede remover ya evaluo');
                return $this->redirect($this->generateUrl('oferta', array('id' => $oferta->getId())));
                }
                return $this->redirect($this->generateUrl('oferta', array('id' => $oferta->getId())));
                } 
                else{
                $this->get('session')->getFlashBag()->add('error', 'No permitido');          
                return $this->redirect($this->generateUrl('oferta', array('id' => $oferta->getId())));   
                }
            
        }
            

    
}
