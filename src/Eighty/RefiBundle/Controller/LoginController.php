<?php

namespace Eighty\RefiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

class LoginController extends Controller
{
    public function loginAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            // redirect authenticated users to homepage
            return $this->redirect($this->generateUrl('refi_homepage'));
        }

        $session = $request->getSession();
        $error = "";

		// get the login error if there is one
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(
				SecurityContext::AUTHENTICATION_ERROR
			);
		} else {
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}


        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return $this->render(
            'RefiBundle:Login:login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error'         => $error
            )
        );
    }

	public function registerAction(Request $request)
    {
		$encoder = $this->get('security.encoder_factory')->getEncoder('Eighty\RefiBundle\Entity\Client');
		$em = $this->getDoctrine()->getManager();

		$postdata['fullname'] = $request->request->get('register-firstname') . ' ' . $request->request->get('register-lastname');
		$postdata['email'] = $request->request->get('register-email');
		$postdata['salt'] = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
		$postdata['password'] = $encoder->encodePassword($request->request->get('register-password'), $postdata['salt']);

        $em->getRepository('RefiBundle:Client')->registerUser($postdata);

        return $this->render(
            'RefiBundle:Login:login.html.twig',
            array(
                'last_username' => '',
                'error'         => ''
            )
        );
    }
}
