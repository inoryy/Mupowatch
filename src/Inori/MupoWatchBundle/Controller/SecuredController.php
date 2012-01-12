<?php

namespace Inori\MupoWatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecuredController extends Controller
{
    public function indexAction()
    {
        return new RedirectResponse($this->generateUrl('sonata_admin_dashboard'));
    }

    public function adminAction()
    {

    }

    public function loginAction()
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('InoriMupoWatchBundle:Secured:login.html.twig', array(
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }

    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    public function logoutAction()
    {
        // The security layer will intercept this request
    }

}
