<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Instrumento;
use App\Entity\Periodoe;
use Admin\UnadBundle\Entity\Docente;


class DefaultController extends AbstractController {

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $periodos = $em->getRepository('App:Periodoe')->findby(array('type' => 'p', 'year' => $this->getParameter('appmed.year')), array('id' => 'DESC'));
        $periodoe = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $this->getParameter('appmed.periodo')));
        $instrumentos = $em->getRepository('App:Instrumentos')->findBy(array('periodoe' => $periodoe));

        $diff = date_diff($periodoe->getFechainicio(), $periodoe->getFechafin());
        $diff2 = date_diff($periodoe->getFechainicio(), new \DateTime('now'));
        $dias = $diff->format("%a");
        $hoy = $diff2->format("%a");

        $session = $request->getSession();
        $session->set('periodoe', $this->getParameter('appmed.periodo'));

        if (true === $this->container->get('security.authorization_checker')->isGranted('ROLE_DEC')) {
            $escuelas = $this->getUser()->getDecano();
            $escuela = $escuelas[0];
            $session->set('escuelaid', $escuela->getId());
        } else {
            $escuela = null;
        }

        if (true === $this->container->get('security.authorization_checker')->isGranted('ROLE_LP')) {
            $entities = $em->getRepository('App:Programa')->findBy(array('lider' => $this->getUser()));
            $escuela = $entities[0]->getEscuela();
            $escuelaid = ($escuela != null)? $escuela->getId() : 65000;
            $session->set('escuelaid', $escuelaid );
        } else {
            $escuela = null;
        }

        if (true === $this->container->get('security.authorization_checker')->isGranted('ROLE_SECA')) {
            $escuelas = $this->getUser()->getSecretaria();
            $escuela = $escuelas[0];
            $session->set('escuelaid', $escuela->getId());
        } else {
            $escuela = null;
        }

        if (true === $this->container->get('security.authorization_checker')->isGranted('ROLE_DIRZ')) {
            $zonas = $this->getUser()->getDirectorzona();
            $zona = $zonas[0];
            $session->set('zonaid', $zona->getId());
        } else {
            $escuela = null;
        }


        $user = $this->getUser()->getUsername();

        if (true === $this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            $docente = $em->getRepository('App:Docente')->findOneBy(array('user' => $this->getUser(), 'periodo' => $this->getParameter('appmed.periodo')));

            if (!$docente) {
                $this->get('session')->getFlashBag()->add('warning', 'Sin activar en el periodo actual');
            } else {
                $session->set('docenteid', $docente->getId());

                return $this->redirect($this->generateUrl('docente_inicio'));
            }
        } else {
            $docente = null;
        }

        return $this->render('Default/index.html.twig', array(
                    'escuela' => $escuela,
                    'user' => $user,
                    'periodo' => $periodoe,
                    'instrumentos' => $instrumentos,
                    'periodos' => $periodos,
                    'dias' => $dias,
                    'hoy' => $hoy
        ));
    }

    public function periodAction(Request $request)
    {
        $session = $request->getSession();
        $escuelaid = $session->get('escuelaid');
        if ($escuelaid == null) {
            return $this->redirect($this->generateUrl('home_user_inicio'));
        }
        $em = $this->getDoctrine()->getManager();
        $year = $this->getParameter('appmed.year');
        $roles = $this->getUser()->getUserRoles();
        $isdc = false;
        foreach ($roles as $role){
            if ($role->getName() == 'ROLE_DC'){
                $isdc = true;
            }
        }
        if (true === $this->container->get('security.authorization_checker')->isGranted('ROLE_LP')) {
            $entities = $em->getRepository('App:Programa')->findBy(array('lider' => $this->getUser()));
            $escuela = $entities[0]->getEscuela();
            $escuelaid = ($escuela != null)? $escuela->getId() : 65000;
            $session->set('escuelaid', $escuelaid );
        }
        $periodos_on = $this->getParameter('appmed.periodos');
        $periodos = $em->getRepository('App:Periodoe')->findBy(array('id' => $periodos_on));
        return $this->render('Default/periods.html.twig', array(
            'year' => $year,
            'periodos' => $periodos,
            'isdc' => $isdc
        ));
    }

    public function selectAction(Request $request, $id){
        $session = $request->getSession();
        $session->set('periodoe', $id);
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $docente = $em->getRepository('App:Docente')->findOneBy(array('user' => $user, 'periodo' => $id));
        if (!$docente) {
            $this->get('session')->getFlashBag()->add('warning', 'Sin activar en el periodo actual');
            return $this->redirect($this->generateUrl('home_user_periodo'));
        } else {
            $session->set('docenteid', $docente->getId());
            return $this->redirect($this->generateUrl('docente_inicio'));
        }
    }


    public function homeAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $instrumentos = $em->getRepository('App:Instrumento')->findAll();
        $periodo = $em->getRepository('App:Periodoe')->findOneBy(array('id' => $this->getParameter('appmed.periodo')));
        $periodos = $em->getRepository('App:Periodoe')->findby(array('year' => $this->getParameter('appmed.year')));

        $session = $request->getSession();

        $cedula_usuario = $request->request->get('cedula_usuario');

        $user = $em->getRepository('App:User')->findOneBy(array('id' => $cedula_usuario));

        $nombres_usuario = $request->request->get('nombres_usuario');
        $apellidos_usuario = $request->request->get('apellidos_usuario');
        $email_usuario = $request->request->get('email_usuario');
        $autenticacion = $request->request->get('autenticacion');
        $login_usuario = $request->request->get('login_usuario');

        $url_autenticacion = "http://login.unad.edu.co/";
        $url_autenticacion2 = "https://intranet.unad.edu.co/autenticacion.php";
        $urlInicioApp = "http://med.unad.edu.co/";
        //$urlServerApp  = "/login_check";
        //$direccion_respuesta = $this->getRequest()->server->get('HTTP_REFERER');
        $direccion_respuesta = $request->server->get('HTTP_REFERER');
        $direccion_ip = $request->server->get('REMOTE_ADDR');
        //$direccion_respuesta = $request->getPathInfo();
        //------------- Origenes validos ----------------------------------------------------------
        $urlOrigenValido1 = "https://intranet.unad.edu.co/autenticacion.php?continue=http://med.unad.edu.co/"; //cuando accede por el home de intranet
        $urlOrigenValido2 = $url_autenticacion . "Usuario/envioDatosUsuario.php"; //cuando accede por login.unad.edu.co
        $urlOrigenValido3 = $url_autenticacion . "Usuario/envioDatosUsuario.php?continue=" . $urlInicioApp; //cuando accede por login.unad.edu.co
        //
        //-----------------------------------------------------------------------------------------

        $ucount = (isset($user)) ? 1 : 0;

        if ($autenticacion == "Aceptada" && $ucount == 1) {
            $this->ingresoAction($cedula_usuario, $request);
        } else {
            # $this->ingresoAction($cedula_usuario);
            return $this->render('Default/home.html.twig', array(
                        // el último nombre de usuario ingresado por el usuario
                        'cedula_usuario' => $cedula_usuario,
                        'nombres_usuario' => $nombres_usuario,
                        'apellidos_usuario' => $apellidos_usuario,
                        'autenticacion' => $autenticacion,
                        'direccion_respuesta' => $direccion_respuesta,
                        'email_usuario' => $email_usuario,
                        'instrumentos' => $instrumentos,
                        'periodo' => $periodo,
                        'periodos' => $periodos,
                        'user' => $user,
                        'login_usuario' => $login_usuario,
                        'direccion_ip' => $direccion_ip,
                        'ucount' => $ucount
            ));
        }
    }

    public function sendAction() {
        $request = $this->getRequest();
        $session = $request->getSession();
        $cedula_usuario = $session->get('cedula_usuario');
        $pass = $request->server->get('MED_PKW');
        $formulario = "<form method='post' name='datos' action='/login_check'>";
        $formulario .= "<input id='username' type='hidden' name='_username' value=$cedula_usuario />";
        $formulario .= "<input id='password' type='hidden' name='_password' value=$pass />";
        $formulario .= "</form>";
        $formulario .= "<script>document.forms[0].submit(); </script>";
        echo $formulario;
    }

    public function ingresoAction($cedula_usuario, $request) {
        $pass = $request->server->get('MED_PKW');
        $formulario = "<form method='post' name='datos' action='/login_check'>";
        $formulario .= "<input id='username' type='hidden' name='_username' value=$cedula_usuario />";
        $formulario .= "<input id='password' type='hidden' name='_password' value=$pass />";
        $formulario .= "</form>";
        $formulario .= "<script>document.forms[0].submit(); </script>";
        echo $formulario;
    }



}
