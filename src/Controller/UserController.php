<?php

namespace App\Controller;

use App\Form\CedulaType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Entity\Parabuscar;
use App\Entity\User;
use App\Form\UserType;
use App\Form\BuscarType;
use App\Form\PassType;
use App\Entity\Newpass;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;


/**
 * User controller.
 *
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }
    /**
     * Lists all Usuarios entities.
     * @Route("/", name="admin_user", methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->doctrine->getManager();
        $dql = "select a from App:User a ORDER BY a.updated DESC";
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

        return $this->render('User/index.html.twig', array(
            'entities' => $entities,
            'form' => $Form->createView(),
            'newform' => $newform->createView()
        ));
    }

    /**
     * Lists all Usuarios entities.
     */
    public function infoAction()
    {
        return $this->render('User/info.html.twig');
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
        $em = $this->doctrine->getManager();
        if ($parametro == 'ced') {
            $query = $em->createQuery('SELECT a FROM App:User a WHERE a.id LIKE :text');
        } elseif ($parametro == 'nom') {
            $query = $em->createQuery('SELECT a FROM App:User a WHERE a.nombres LIKE :text');
        } elseif ($parametro == 'apell') {
            $query = $em->createQuery('SELECT a FROM App:User a WHERE a.apellidos LIKE :text ');
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
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createForm(UserType::class, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->setSecurePassword($entity);
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_show', array('id' => $entity->getId())));
        }

        return $this->render('User/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/new", name="admin_user_new", methods={"GET"})
     */
    public function newAction()
    {
        $entity = new User();
        $form = $this->createForm(UserType::class, $entity);

        return $this->render('User/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     */
    public function showAction($id)
    {
        $em = $this->doctrine->getManager();

        $archivo = $em->getRepository('App:Archivo')->findBy(array('cedula' => $id));

        $porSemestre = $em->getRepository('App:Docente')->porSemestres($id, "'s'");
        $porAnual = $em->getRepository('App:Docente')->porSemestres($id, "'a'");
        $porPeriodo = $em->getRepository('App:Docente')->porSemestres($id, "'p'");


        $entity = $em->getRepository('App:User')->find($id);
        $roles = $entity->getUserRoles();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $passForm = $this->createCedulaForm($entity);

        return $this->render('User/show.html.twig', array(
            'entity' => $entity,
            'newpass_form' => $passForm->createView(),
            'archivo' => $archivo,
            'semestres' => $porSemestre,
            'anuales' => $porAnual,
            'periodos' => $porPeriodo,
            'roles' => $roles
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET"})
     */
    public function editAction($id)
    {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:User')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('User/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/{id}/update", name="admin_user_update", methods={"PUT"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:User')->find($id);
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
        $form = $this->createForm(UserType::class, $entity, array(
            'action' => $this->generateUrl('admin_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Update'));
        return $form;
    }

    /**
     * Lists all Usuarios entities.
     * @Route("/{id}/newpass", name="admin_user_newpass", methods={"POST"})
     */
    public function newpassAction(Request $request, MailerInterface $mailer, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('App:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $passForm = $this->createCedulaForm($entity);
        $passForm->handleRequest($request);

        if ($passForm->isValid()) {
            $currentPass = $this->generateRandomString();
            $entity->setPassword($currentPass);
            $this->setSecurePassword($entity);
         //   $this->enviarMail($entity, $currentPass);
            $this->sendEmail($mailer, $entity, $currentPass);
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
        #$entity->setSalt(md5(time()));
        #$encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        // Retrieve the right password hasher by its name
        // Configure different password hashers via the factory
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium'],
        ]);
        $passwordHasher = $factory->getPasswordHasher('common');
        $hash = $passwordHasher->hash($entity->getPassword());
        #$password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($hash);
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

    public function sendEmail($mailer, $user, $newpass)
    {
        $template = $this->render('User/mailnewpass.html.twig', array('user' => $user->getId(), 'newpass' => $newpass ));
        $email = (new Email())
            ->from(new Address('soporte.med@unad.edu.co', 'Módulo de Evaluación Docente MED'))
            ->to(new Address($user->getEmail(),$user->getNombres() . ' ' . $user->getApellidos()))
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Contraseña del Módulo MED para ' . $user->getId())
            ->html($template->getContent());
        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            Return 'Error';
        }
    }

    public function passmedAction()
    {
        $valores = new Newpass();
        $Form = $this->createForm(PassType::class, $valores);
        return $this->render('Default/passmed.html.twig', array(
            'form' => $Form->createView(),
        ));
    }

    public function setpassAction(Request $request,  MailerInterface $mailer)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $em = $this->doctrine->getManager();
        $valores = new Newpass();
        $Form = $this->createForm(PassType::class, $valores);

        $Form->handleRequest($request);

        if ($Form->isValid()) {
            $username = $Form["username"]->getData();
            $email = $Form["email"]->getData();
            $unidad = (int)$Form["unidad"]->getData();

            $user = $em->getRepository('App:User')->find($username);
            $docente = $em->getRepository('App:Docente')->findOneBy(array('user' => $user, 'periodo' => $this->getParameter('appmed.periodo')));
            $escuela_id = $docente->getEscuela()->getId();

            if (isset($docente) && (strcmp($user->getEmail(), $email) == 0)) {
                if (strcmp($escuela_id, $unidad) == 0) {
                    $currentpass = $this->generateRandomString();
                    $user->setPassword($currentpass);
                    $this->setSecurePassword($user);
                    try {
                        $this->sendEmail($mailer, $user, $currentpass);
                    } catch (\Exception $e) {
                        $pass = $request->server->get('MED_PKW');
                        $user->setPassword($pass);
                        $this->setSecurePassword($user);
                        $em->persist($user);
                        $em->flush();
                        $response = new JsonResponse(
                            array(
                                'message' => '<div class="alert alert-warning fade in"><i class="fa-fw fa fa-check"></i><strong>Error !</strong> Error al enviar el correo. Se restablecio su ingreso mediante intranet<a href="http://intranet.unad.edu.co/"> Continuar..</a></div>',
                                'form' => $this->renderView('Default/passmed.html.twig', array(
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
                            'form' => $this->renderView('Default/passmed.html.twig', array(
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
                            'form' => $this->renderView('Default/passform.html.twig', array(
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
                        'form' => $this->renderView('Default/passform.html.twig', array(
                            'form' => $Form->createView(),
                        ))),
                    400
                );
                return $response;
            }
        }
        $response = new JsonResponse(
            array(
                'form' => $this->renderView('Default/passform.html.twig', array(
                    'form' => $Form->createView(),
                ))),
            400
        );
        return $response;
    }
}
