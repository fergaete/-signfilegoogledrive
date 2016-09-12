<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller {
    
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function loginAction(Request $request) {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error               = $authenticationUtils->getLastAuthenticationError();
        $lastUsername        = $authenticationUtils->getLastUsername();        
        return $this->render(
            'AppBundle:Admin:login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error'         => $error
            )
        );
    }
    
    /**
     * @Route("/admin/login-check", name="admin_login_check")
     */
    public function loginCheckAction(Request $request) {
    }
    
    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logoutAction(Request $request) {
    }    
}