<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ComponentsController extends Controller {
	
	public function navbarAction() {
		$user = null;
		if($this->getUser()) {
			$email   = $this->getUser()->getUsername();
	        $usuario = $this->get('app.repository.usuario')->findOneBy(array(
	            'email' => $email 
	        ));
	        
	        $credential = $usuario->getGoogleAccount()->getCredential();
	        $user = $this->get('app.service.google.user_service')->getUserInfo($credential);
	    }

        return $this->render('AppBundle:Components:navbar.html.twig', array('user' => $user));
	}
	
	public function footerAction(){
		return $this->render('AppBundle:Components:footer.html.twig', array());
	}

	public function flagsAction() {
		return $this->render('AppBundle:Components:flags.html.twig');
	}
}