<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Plangestion;
use AppBundle\Entity\Proyectoi;
use AppBundle\Entity\Productividad;
use AppBundle\Form\ProductividadType;
use AppBundle\Form\ProyectoiType;

/**
 * Plangestion controller.
 *
 * @Route("/doc/prodinv")
 */
class ProductividadController extends Controller
{
    /**
     * Displays a form to create a new Actividadplang entity.
     *
     * @Route("/new/{tipo}", name="productividad_new", methods={"GET"})
     * @Template("Productividad/new.html.twig")
     */
    public function newAction(Request $request, $tipo)
    {
        $entity = new Productividad();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $docenteid = $session->get('docenteid');
        $user = $this->getUser()->getUsername();

        $plang = $em->getRepository('AppBundle:Plangestion')->find($docenteid);
        $entity->setPlang($plang);

        $form = $this->createProdForm($entity, $user);

        return array(
            'tipo' => $tipo,
            'form' => $form->createView(),
            'docenteid' => $docenteid,
        );
    }

    /**
     * Creates a form to create a Productividad entity.
     * @param Productividad $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createProdForm(Productividad $entity, $user)
    {
        $form = $this->createForm(ProductividadType::class, $entity, ['user' => $user]);
        return $form;
    }


    /**
     * Displays a form to create a new proyectoi entity.
     *
     * @Route("/project/new", name="productividad_projectnew", methods={"GET"})
     * @Template("Productividad/newproject.html.twig")
     */
    public function newprojectAction()
    {
        $entity = new Proyectoi();

        $user = $this->getUser();

        $entity->setUser($user);

        $form = $this->createProyectForm($entity);

        return array(
            'form' => $form->createView()
        );
    }


    /**
     * Creates a form to create a Productividad entity.
     * @param Productividad $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createProyectForm(Proyectoi $entity)
    {
        $form = $this->createForm(ProyectoiType::class, $entity);
        return $form;
    }

    /**
     * Guarda productividad
     * @Route("/add/{id}", name="productividad_add", methods={"POST"})
     * @Template("Productividad/new.html.twig")
     */
    public function addAction(Request $request, $id)
    {
        $entity = new Productividad();
        $em = $this->getDoctrine()->getManager();
        $plang = $em->getRepository('AppBundle:Plangestion')->find($id);
        $user = $this->getUser()->getUsername();

        $entity->setPlang($plang);
        $entity->setFecharegistro(new \Datetime());
        $form = $this->createProdForm($entity, $user);
        $form->handleRequest($request);


        if ($form->isValid()) {
            //$em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('plangestion_crear'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'id' => $id,
        );
    }

    /**
     * Guarda productividad
     * @Route("/project/add", name="productividad_projectadd", methods={"POST"})
     * @Template("Productividad/newproject.html.twig")
     */
    public function addprojectAction(Request $request)
    {
        $entity = new Proyectoi();
        $em = $this->getDoctrine()->getManager();
        //$plang = $em->getRepository('AppBundle:Plangestion')->find($id);
        $user = $this->getUser();

        $entity->setUser($user);
        $entity->setEstado(1);
        $form = $this->createProyectForm($entity);
        $form->handleRequest($request);


        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('plangestion_crear'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Deletes a productividad entity.
     *
     * @Route("/{id}", name="productividad_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Productividad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rolplang entity.');
            }
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('plangestion_crear', array('id' => $entity->getPlang()->getId())));
    }

    /**
     * Creates a form to delete a Rolplang entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('productividad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Borrar', 'attr' => array('class' => 'btn btn-labeled btn-success')))
            ->getForm();
    }


    /**
     * Finds and displays a productividad entity.
     *
     * @Route("/{id}", name="productividad_show", methods={"GET"})
     * @Template("Productividad/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Productividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rolplang entity.');
        }
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
}
