<?php

namespace Admin\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Admin\UserBundle\Entity\Parabuscar;
use Admin\UserBundle\Entity\User;
use Admin\UserBundle\Form\UserType;
use Admin\UserBundle\Form\BuscarType;
use Admin\UserBundle\Form\PassType;
use Admin\UserBundle\Entity\Newpass;

/**
 * User controller.
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{

    /**
     * Lists all Usuarios entities.
     * @Route("/", name="admin_user")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "select a from AdminUserBundle:User a";
        $query = $em->createQuery($dql);
        $query->setMaxResults(50);
        $entities = $query->getResult();

        $valores = new Parabuscar();
        $Form = $this->createForm(BuscarType::class, $valores);

        return $this->render('AdminUserBundle:User:index.html.twig', array(
            'entities' => $entities,
            'buscar_form' => $Form->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Method("GET")
     * @Template()
     */
    public function infoAction()
    {
        return $this->render('AdminUserBundle:User:info.html.twig');
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/", name="admin_user_buscarpor")
     * @Method("PUT")
     * @Template()
     */
    public function buscarporAction(Request $request)
    {
        $valores = new Parabuscar();
        $Form = $this->createForm(BuscarType::class, $valores);
        $Form->handleRequest($request);
        $cadena = $valores->getTexto();
        //$cadena = $Form->get('texto')->getData();
        //$param = $Form->get('parametro')->getData();
        $param = $valores->getParametro();

        if ($param == 'ced') {
            return $this->buscarcedulaAction($cadena);
        } elseif ($param == 'nom') {
            return $this->buscarnombreAction($cadena);
        } elseif ($param == 'apell') {
            return $this->buscarapellidoAction($cadena);
        }
        return $this->redirect($this->generateUrl('_welcome'));
    }

    public function buscarapellidoAction($text)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT a FROM AdminUserBundle:User a WHERE a.apellidos LIKE :text ');
        $query->setParameters(array(
            'text' => '%' . $text . '%',
        ));
        $docentes = $query->getResult();
        $valores = new Parabuscar();
        $Form = $this->createForm(BuscarType::class, $valores);
        return $this->render('AdminUserBundle:User:index.html.twig', array(
            'entities' => $docentes,
            'buscar_form' => $Form->createView(),
        ));
    }

    public function buscarnombreAction($text)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT a FROM AdminUserBundle:User a WHERE a.nombres LIKE :text');
        $query->setParameters(array(
            'text' => '%' . $text . '%',
        ));
        $docentes = $query->getResult();
        $valores = new Parabuscar();
        $Form = $this->createForm(BuscarType::class, $valores);
        return $this->render('AdminUserBundle:User:index.html.twig', array(
            'entities' => $docentes,
            'buscar_form' => $Form->createView(),
        ));
    }

    public function buscarcedulaAction($text)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT a FROM AdminUserBundle:User a WHERE a.id LIKE :text');
        $query->setParameters(array(
            'text' => $text,
        ));
        $docentes = $query->getResult();
        $valores = new Parabuscar();
        $Form = $this->createForm(BuscarType::class, $valores);
        return $this->render('AdminUserBundle:User:index.html.twig', array(
            'entities' => $docentes,
            'buscar_form' => $Form->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/create", name="admin_user_create")
     * @Method("POST")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createForm(UserType::class, $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $this->setSecurePassword($entity);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_show', array('id' => $entity->getId())));
        }

        return $this->render('AdminUserBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/new", name="admin_user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form = $this->createForm(UserType::class, $entity);

        return $this->render('AdminUserBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();


        $archivo = $em->getRepository('AdminMedBundle:Archivo')->findBy(array('cedula' => $id));

        $entity = $em->getRepository('AdminUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $passForm = $this->createPassForm($id);

        return $this->render('AdminUserBundle:User:show.html.twig', array(
            'entity' => $entity,
            'newpass_form' => $passForm->createView(),
            'archivo' => $archivo,
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(UserType::class, $entity);

        return $this->render('AdminUserBundle:User:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/{id}/update", name="admin_user_update")
     * @Method("PUT")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminUserBundle:User')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $editForm = $this->createForm(UserType::class, $entity);
        //  $currentpass = $entity->getPassword();
        $pass = $request->server->get('MED_PKW');
        $editForm->bind($request);
        $entity->setPassword($pass);
        $this->setSecurePassword($entity);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            //  return $this->redirect($this->generateUrl('admin_user_edit', array('id' => $id)));
            return $this->render('AdminUserBundle:User:edit.html.twig', array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ));
        }
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/{id}/newpass", name="admin_user_newpass")
     * @Method("POST")
     * @Template("AdminUserBundle:User:show.html.twig")
     */
    public function newpassAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            //     return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $passForm = $this->createPassForm($id);
        $passForm->bind($request);
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
     * @Route("/{id}/delete", name="admin_user_delete")
     * @Method("DELETE")
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminUserBundle:User')->find($id);

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
     * Creates a form to delete a Instrumento entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createPassForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_newpass', array('id' => $id)))
            ->setMethod('POST')
            ->getForm();
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

    public function enviarMail(\Admin\UserBundle\Entity\User $user, $newpass)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Contraseña del Módulo MED para ' . $user->getId())
            ->setFrom(array('siga@unad.edu.co' => 'Módulo de Evaluación Docente MED'))
            ->setTo(array($user->getEmail() => $user->getNombres() . ' ' . $user->getApellidos()))
            ->setBody(
                $this->renderView(
                    'AdminUserBundle:User:newpass.txt.twig',
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
        return $this->render('AdminUserBundle:Default:passmed.html.twig', array(
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


            $user = $em->getRepository('AdminUserBundle:User')->find($username);
            $docente = $em->getRepository('AdminUnadBundle:Docente')->findOneBy(array('user' => $user, 'periodo' => $this->container->getParameter('appmed.periodo')));
            $escuela_id = $docente->getEscuela()->getId();
            $docente_vinculacion = $docente->getVinculacion();

            if (isset($docente) && (strcmp($user->getEmail(), $email)==0)) {
                if ((strcmp($docente_vinculacion, $vinculacion)==0) && strcmp($escuela_id, $unidad)==0) {
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
                                'form' => $this->renderView('AdminUserBundle:Default:passmed.html.twig', array(
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
                            'form' => $this->renderView('AdminUserBundle:Default:passmed.html.twig', array(
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
                            'form' => $this->renderView('AdminUserBundle:Default:passform.html.twig', array(
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
                        'form' => $this->renderView('AdminUserBundle:Default:passform.html.twig', array(
                            'form' => $Form->createView(),
                        ))),
                    400
                );
                return $response;
            }
        }
        $response = new JsonResponse(
            array(
                'form' => $this->renderView('AdminUserBundle:Default:passform.html.twig', array(
                    'form' => $Form->createView(),
                ))),
            400
        );
        return $response;
    }
}
