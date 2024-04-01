<?php
// proyecto/src/MDW/BlogBundle/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends AbstractController
{
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        // obtiene el error de inicio de sesión si lo hay

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
        $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {

          $error = $session->get(Security::AUTHENTICATION_ERROR);
        }
         return $this->render('Security/login.html.twig', array(
            // el último nombre de usuario ingresado por el usuario
            'last_username' => $session->get(Security::LAST_USERNAME),
            'error'         => $error,
            ));
        //$this->get('session')->getFlashBag()->add('error', $error->getMessage().' '.$session->get(SecurityContext::LAST_USERNAME).' No corresponde a un usuario en el Módulo MED');
       // return $this->redirect($this->generateUrl('admin_user_home'));
    }
}
