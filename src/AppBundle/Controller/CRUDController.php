<?php
namespace AppBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CRUDController extends Controller {
	
	/**
     * @return RedirectResponse
     */
    public function logAction() {
        return new RedirectResponse(
            $this->generateUrl(
                'historial_list', 
                array(
                    'filter[usuario][value]' => $this->get('request')->get($this->admin->getIdParameter())
                )
        ));
    }
}