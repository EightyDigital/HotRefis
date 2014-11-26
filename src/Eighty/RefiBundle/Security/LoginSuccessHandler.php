<?php

namespace Eighty\RefiBundle\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;

use Eighty\RefiBundle\Entity\Clientlogin;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
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
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
		if ($this->security->isGranted('ROLE_ADMIN'))
		{
			$this->_clientLoginLog($request->getClientIp());
			$response = new RedirectResponse($this->router->generate('refi_homepage'));			
		}
		elseif ($this->security->isGranted('ROLE_USER'))
		{
			$this->_clientLoginLog($request->getClientIp());
			if(count($this->em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($this->security->getToken()->getUser()->getId())) > 0)
				$referer_url = $this->router->generate('refi_campaign');
			else
				$referer_url = $request->headers->get('referer');
						
			$response = new RedirectResponse($referer_url);
		}
			
		return $response;
	}

	private function _clientLoginLog($ip)
	{
		$clientLogin = new Clientlogin();
		$clientLogin->setClientId($this->security->getToken()->getUser()->getId());
		$clientLogin->setLoginDate(new \DateTime());
		$clientLogin->setClientIp($ip);
		$this->em->persist($clientLogin);
		$this->em->flush();
	}
	
}