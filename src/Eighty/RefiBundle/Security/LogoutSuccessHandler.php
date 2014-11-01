<?php

namespace Eighty\RefiBundle\Security;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Doctrine\ORM\EntityManager;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
	
	protected $router;
	protected $security;
	protected $em;
	
	public function __construct(Router $router, SecurityContext $security, EntityManager $em)
	{
		$this->router = $router;
		$this->security = $security;
		$this->em = $em;
	}
	
	public function onLogoutSuccess(Request $request)
	{
		// 	var_dump($request->request->all()); exit();
		// $this->_clientLogoutLog($this->security->getToken()->getUser()->getId());
		
		$response = new RedirectResponse($this->router->generate('refi_homepage'));		
		return $response;
	}

	private function _clientLogoutLog($id)
	{
		$clientLogout = $this->em->getRepository('RefiBundle:Clientlogin')->findOneByClientId($id);
		$clientLogout->setLogoffDate(new \DateTime());
		$this->em->flush();
	}
	
}