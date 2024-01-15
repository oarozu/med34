<?php

namespace AppBundle\Controller;

use AppBundle\Form\CedulaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Parabuscar;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\BuscarType;
use AppBundle\Form\PassType;
use AppBundle\Entity\Newpass;

/**
 * User controller.
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{

    /**
     * Lists all Usuarios entities.
     * @Route("/", name="admin_user", methods={"GET"})
     * @Template("AppBundle:User:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "select a from AppBundle:User a ORDER BY a.updated DESC";
        $query = $em->createQuery($dql);
        $query->setMaxResults(200);
        $entities = $query->getResult();


        $valores = new Parabuscar();
        $Form = $this->createForm(BuscarType::class, $valores);

        $newform = $this->createFormBuilder($valores)
            ->setMethod('GET')
            ->add('texto')
            ->add('parametro', ChoiceType::class, array(
                'placeholder' => 'Buscar por:',
                'choices' => array('Cédula' => 'ced', 'Nombres' => 'nom', 'Apellidos' => 'apell'),
                'required' => true,
            ))
            ->add('save', SubmitType::class, ['label' => 'Buscar Usuario'])
            ->getForm();

        $newform->handleRequest($request);

        if ($newform->isSubmitted() && $newform->isValid()) {
            $valores = $newform->getData();
            $entities = $this->buscarDocenteAction($valores->getTexto(), $valores->getParametro());
        }

        return $this->render('AppBundle:User:index.html.twig', array(
            'entities' => $entities,
            'form' => $Form->createView(),
            'newform' => $newform->createView()
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Template()
     */
    public function infoAction()
    {
        return $this->render('AppBundle:User:info.html.twig');
    }

    /**
     * Creates a form to edit a Avalplang entity.
     *
     * @param Parabuscar $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createBuscarForm(Parabuscar $entity)
    {
        $form = $this->createForm(BuscarType::class, $entity, array(
            'action' => $this->generateUrl('admon_user_buscarpor'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'New Buscar'));

        return $form;
    }

    public function buscarDocenteAction($text, $parametro)
    {
        $em = $this->getDoctrine()->getManager();
        if ($parametro == 'ced') {
            $query = $em->createQuery('SELECT a FROM AppBundle:User a WHERE a.id LIKE :text');
        } elseif ($parametro == 'nom') {
            $query = $em->createQuery('SELECT a FROM AppBundle:User a WHERE a.nombres LIKE :text');
        } elseif ($parametro == 'apell') {
            $query = $em->createQuery('SELECT a FROM AppBundle:User a WHERE a.apellidos LIKE :text ');
        }
        $query->setMaxResults(200);
        $query->setParameters(array(
            'text' => '%' . $text . '%',
        ));
        $docentes = $query->getResult();
        return $docentes;
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/create", name="admin_user_create", methods={"POST"})
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createForm(UserType::class, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->setSecurePassword($entity);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/new", name="admin_user_new", methods={"GET"})
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form = $this->createForm(UserType::class, $entity);

        return $this->render('AppBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $archivo = $em->getRepository('AdminMedBundle:Archivo')->findBy(array('cedula' => $id));

        $porSemestre = $em->getRepository('AdminUnadBundle:Docente')->porSemestres($id,"'s'");
        $porAnual = $em->getRepository('AdminUnadBundle:Docente')->porSemestres($id,"'a'");
        $porPeriodo = $em->getRepository('AdminUnadBundle:Docente')->porSemestres($id,"'p'");


        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $passForm = $this->createCedulaForm($entity);

        return $this->render('AppBundle:User:show.html.twig', array(
            'entity' => $entity,
            'newpass_form' => $passForm->createView(),
            'archivo' => $archivo,
            'semestres'=> $porSemestre,
            'anuales'=> $porAnual,
            'periodos'=> $porPeriodo
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET"})
     * @Template("AppBundle:User:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:User')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('AppBundle:User:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/{id}/update", name="admin_user_update", methods={"PUT"})
     * @Template("AppBundle:User:edit.html.twig")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:User')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $editForm = $this->createEditForm($entity);
        $pass = $request->server->get('MED_PKW');
        $editForm->handleRequest($request);
        $entity->setPassword($pass);
        $this->setSecurePassword($entity);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_user_edit', array('id' => $id)));
        }
        return $this->editAction($id);
    }


    /**
     * Creates a form to edit a User entity.
     * @param User $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm( UserType::class, $entity, array(
            'action' => $this->generateUrl('admin_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Update'));
        return $form;
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/{id}/newpass", name="admin_user_newpass", methods={"POST"})
     * @Template("AppBundle:User:show.html.twig")
     */
    public function newpassAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $passForm = $this->createCedulaForm($entity);
        $passForm->handleRequest($request);

        if ($passForm->isValid()) {
            $currentPass = $this->generateRandomString();
            $entity->setPassword($currentPass);
            $this->setSecurePassword($entity);
            $this->enviarMail($entity, $currentPass);
            $em->persist($entity);
            $em->flush();
            return new JsonResponse(array(
                'message' => '<div class="alert alert-success fade in"><i class="fa-fw fa fa-check"></i><strong>Hecho !</strong> Nueva Contraseña: ' . $currentPass . '</div>'), 200);
        }
        return new JsonResponse(
            array(
                'message' => 'Error desde Json'),
            400
        );
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/{id}/delete", name="admin_user_delete", methods={"DELETE"})
     * @Template("AppBundle:User:delete.html.twig")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }


    /**
     * Creates a form to edit a Instrumento entity.
     *
     * @param User $user The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCedulaForm(User $user)
    {
        $form = $this->createForm(CedulaType::class, $user, array(
            'action' => $this->generateUrl('admin_instrumento_update', array('id' => $user->getId()))
        ));
        return $form;
    }

    private function setSecurePassword(&$entity)
    {
        $entity->setSalt(md5(time()));
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
    }

    private function generateRandomString($length = 6)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function enviarMail(\AppBundle\Entity\User $user, $newpass)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Contraseña del Módulo MED para ' . $user->getId())
            ->setFrom(array('siga@unad.edu.co' => 'Módulo de Evaluación Docente MED'))
            ->setTo(array($user->getEmail() => $user->getNombres() . ' ' . $user->getApellidos()))
            ->setBody(
                $this->renderView(
                    'AppBundle:User:newpass.txt.twig',
                    array('user' => $user,
                        'newpass' => $newpass
                    )
                )
            );
        if ($user->getEmailp() != '') {
            $message->setCc(array($user->getEmailp() => $user->getNombres() . ' ' . $user->getApellidos()));
        }
        $this->get('mailer')->send($message);
    }

    public function passmedAction()
    {
        $valores = new Newpass();
        $Form = $this->createForm(PassType::class, $valores);
        return $this->render('AppBundle:Default:passmed.html.twig', array(
            'form' => $Form->createView(),
        ));
    }

    public function setpassAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $em = $this->getDoctrine()->getManager();
        $valores = new Newpass();
        $Form = $this->createForm(PassType::class, $valores);

        $Form->handleRequest($request);

        if ($Form->isValid()) {
            $username = $Form["username"]->getData();
            $email = $Form["email"]->getData();
            $vinculacion = $Form["vinculacion"]->getData();
            $unidad = (int)$Form["unidad"]->getData();


            $user = $em->getRepository('AppBundle:User')->find($username);
            $docente = $em->getRepository('AdminUnadBundle:Docente')->findOneBy(array('user' => $user, 'periodo' => $this->container->getParameter('appmed.periodo')));
            $escuela_id = $docente->getEscuela()->getId();
            $docente_vinculacion = $docente->getVinculacion();

            if (isset($docente) && (strcmp($user->getEmail(), $email) == 0)) {
                if ((strcmp($docente_vinculacion, $vinculacion) == 0) && strcmp($escuela_id, $unidad) == 0) {
                    $currentpass = $this->generateRandomString();
                    $user->setPassword($currentpass);
                    $this->setSecurePassword($user);
                    try {
                        $this->enviarMail($user, $currentpass);
                    } catch (\Exception $e) {
                        $pass = $request->server->get('MED_PKW');
                        $user->setPassword($pass);
                        $this->setSecurePassword($user);
                        $em->persist($user);
                        $em->flush();
                        $response = new JsonResponse(
                            array(
                                'message' => '<div class="alert alert-warning fade in"><i class="fa-fw fa fa-check"></i><strong>Error !</strong> Error al enviar el correo. Se restablecio su ingreso mediante intranet<a href="http://intranet.unad.edu.co/"> Continuar..</a></div>',
                                'form' => $this->renderView('AppBundle:Default:passmed.html.twig', array(
                                    'form' => $Form->createView(),
                                ))),
                            200
                        );
                        return $response;
                    }

                    $em->persist($user);
                    $em->flush();
                    $Form = $this->createForm(PassType::class, $valores);
                    $response = new JsonResponse(
                        array(
                            'message' => '<div class="alert alert-success fade in"><i class="fa-fw fa fa-check"></i><strong>Hecho !</strong> Se genero una nueva contraseña de ingreso al MED y se envio a su correo institucional con las instrucciones. <a href="../login">Continuar..</a></div>',
                            'form' => $this->renderView('AppBundle:Default:passmed.html.twig', array(
                                'form' => $Form->createView(),
                            ))),
                        200
                    );
                    return $response;
                } else {
                    $Form = $this->createForm(PassType::class, $valores);
                    $response = new JsonResponse(
                        array(
                            'message' => '<div class="alert alert-danger fade in"><i class="fa-fw fa fa-times"></i><strong>Error !</strong> La información suministrada no coincide con la información registrada.</div>',
                            'form' => $this->renderView('AppBundle:Default:passform.html.twig', array(
                                'form' => $Form->createView(),
                            ))),
                        400
                    );
                    return $response;
                }
            } else {
                $Form = $this->createForm(PassType::class, $valores);
                $response = new JsonResponse(
                    array(
                        'message' => '<div class="alert alert-danger fade in"><i class="fa-fw fa fa-times"></i><strong>Error !</strong> La información suministrada no coincide con la información registrada.</div>',
                        'form' => $this->renderView('AppBundle:Default:passform.html.twig', array(
                            'form' => $Form->createView(),
                        ))),
                    400
                );
                return $response;
            }
        }
        $response = new JsonResponse(
            array(
                'form' => $this->renderView('AppBundle:Default:passform.html.twig', array(
                    'form' => $Form->createView(),
                ))),
            400
        );
        return $response;
    }
}
