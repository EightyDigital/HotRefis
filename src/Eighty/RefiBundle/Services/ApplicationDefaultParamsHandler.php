<?php

namespace Eighty\RefiBundle\Services;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;

class ApplicationDefaultParamsHandler
{
	protected $security;
	protected $em;
	protected $max_sectors;
	protected $credit_per_prospect;
	
	public function __construct(SecurityContext $security, EntityManager $em, $max_sectors, $credit_per_prospect)
	{
		$this->security = $security;
		$this->em = $em;
		$this->max_sectors = $max_sectors;
		$this->credit_per_prospect = $credit_per_prospect;
	}
	
	public function getDefaultParams()
	{
		$usr = $this->security->getToken()->getUser();
		$data = array();
		
		$data['id'] = $usr->getId();
		$data['name'] = $usr->getFullname();
		$data['email'] = $usr->getEmail();
		$data['address'] = $usr->getAddress();
		$data['company'] = $usr->getAgency();
		$data['company_id'] = $usr->getAgencyId();
		$data['phone'] = $usr->getPhone();
		$data['title'] = $usr->getTitle();
		$data['age'] = $usr->getAge();
		$data['years_as_a_broker'] = $usr->getYears();
		
		$data['credits'] = $this->em->getRepository('RefiBundle:Client')->getRemainingCreditsById($data['id']);
		$data['sectors_owned'] = count($this->em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($data['id']));
		$data['max_sectors'] = $this->max_sectors;
		$data['credit_per_prospect'] = $this->credit_per_prospect;
		
		return $data;
	}

}