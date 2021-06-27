<?php

namespace Admin\MedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MedBundle\Entity\Archivo;
use Admin\MedBundle\Form\ArchivoType;

/**
 * Archivo controller.
 *
 * @Route("/user/archivo")
 */
class ArchivoController extends Controller
{
    
     /**
     * Lists all Archivo entities.
     *
     * @Route("/doc/{ced}", name="archivo_pordoc")
     * @Method("GET")
     * @Template()
     */
    public function pordocAction($ced)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMedBundle:Archivo')->findBy(array('cedula' => $ced));

        return array(
            'entities' => $entities,
        );
    }
    
    
         /**
     * Lists all Archivo entities.
     *
     * @Route("/per/{id}", name="archivo_pordoc")
     * @Method("GET")
     * @Template()
     */
    public function porperiodoAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMedBundle:Archivo')->findBy(array('periodo' => $id));

        return array(
            'entities' => $entities,
            'id'    => $id,
        );
    }
    
    
     /**
     * Lists all Archivo entities.
        * @Route("/docente", name="archivo_docente")
     * @Method("GET")
     * @Template("AdminMedBundle:Archivo:pordoc.html.twig")
     */
    public function docenteAction()
    {
       $em = $this->getDoctrine()->getManager();
       $user = $this->get('security.token_storage')->getToken()->getUser();
       $userId = $user->getId();
       $entities = $em->getRepository('AdminMedBundle:Archivo')->findBy(array('cedula' => $userId));
       return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Creates a new Archivo entity.
     *
     * @Route("/", name="archivo_create")
     * @Method("POST")
     * @Template("AdminMedBundle:Archivo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Archivo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('archivo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Archivo entity.
    *
    * @param Archivo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Archivo $entity)
    {
        $form = $this->createForm(new ArchivoType(), $entity, array(
            'action' => $this->generateUrl('archivo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Archivo entity.
     *
     * @Route("/new", name="archivo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Archivo();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Archivo entity.
     *
     * @Route("/{id}", name="archivo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Archivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivo entity.');
        }
        
        $urlserver = 'https://med.unad.edu.co';
        
        $plan = null;
        $hetero = null;
        $auto = null;
        $coeval = null;
        
        $urlplan = $urlserver.'/archivo/'.$entity->getPeriodo().'/plan/'.$entity->getCedula().'-'.$entity->getPeriodo().'-plan.pdf';
        $urlhetero = $urlserver.'/archivo/'.$entity->getPeriodo().'/hetero/'.$entity->getCedula().'-'.$entity->getPeriodo().'-hetero.pdf';
        $urlauto = $urlserver.'/archivo/'.$entity->getPeriodo().'/auto/'.$entity->getCedula().'-'.$entity->getPeriodo().'-auto.pdf';
        $urlcoeval = $urlserver.'/archivo/'.$entity->getPeriodo().'/coeval/'.$entity->getCedula().'-'.$entity->getPeriodo().'-co.pdf';
        
        if ($this->is_url_exist($urlplan)){
        $plan = $urlplan;      
        }
        if ($this->is_url_exist($urlhetero)){
        $hetero = $urlhetero;      
        }
        if ($this->is_url_exist($urlauto)){
        $auto = $urlauto;      
        }
        if ($this->is_url_exist($urlcoeval)){
        $coeval = $urlcoeval;      
        }   
        
        return array(
            'entity'  => $entity,
            'plan'    => $plan,
            'hetero'  => $hetero,
            'auto'    => $auto,
            'coeval'  => $coeval  
        );
    }

    /**
     * Displays a form to edit an existing Archivo entity.
     *
     * @Route("/{id}/edit", name="archivo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Archivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Archivo entity.
    *
    * @param Archivo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Archivo $entity)
    {
        $form = $this->createForm(new ArchivoType(), $entity, array(
            'action' => $this->generateUrl('archivo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Archivo entity.
     *
     * @Route("/{id}", name="archivo_update")
     * @Method("PUT")
     * @Template("AdminMedBundle:Archivo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMedBundle:Archivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('archivo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Archivo entity.
     *
     * @Route("/{id}", name="archivo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminMedBundle:Archivo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Archivo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('archivo'));
    }

    /**
     * Creates a form to delete a Archivo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('archivo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    public function is_url_exist($url){
    $ch = curl_init($url);    
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($code == 200){
       $status = 1;
    }else{
      $status = 0;
    }
   curl_close($ch);
   return $status;
}
}
