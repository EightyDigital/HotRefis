<?php

namespace Eighty\RefiBundle\Controller;

use Eighty\RefiBundle\Entity\Prospectlist;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    public function indexAction()
    {
        return $this->render('RefiBundle:Application:index.html.twig');
        //, array('name' => $name)
    }

    public function addAction()
    {
        return $this->render('RefiBundle:Application:add.html.twig');
        //, array('name' => $name)
    }
    public function listAction()
    {
        return $this->render('RefiBundle:Application:list.html.twig');
        //, array('name' => $name)
    }

    public function calculatorAction()
    {
        return $this->render('RefiBundle:Application:calculator.html.twig');
        //, array('name' => $name)
    }

    public function reportAction()
    {
        return $this->render('RefiBundle:Application:report.html.twig');
        //, array('name' => $name)
    }

    public function prospectAction()
    {
        return $this->render('RefiBundle:Application:prospect.html.twig');
        //, array('name' => $name)
    }
	
	/*-------------------------------------------------/
	|	route: <domain>/api/filter/property
	|	postdata:
	|		- property_value_min; property_value_max;
	|		- ltv_min; ltv_max;
	|		- loan_age_min; loan_age_max;
	--------------------------------------------------*/
    public function filterPropertyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postdata = $request->request->all();
        
        if (!isset($postdata['property_value_min'])) $postdata['property_value_min'] = 0;
        if (!isset($postdata['property_value_max'])) $postdata['property_value_max'] = 10000000;
        if (!isset($postdata['ltv_min'])) $postdata['ltv_min'] = 0;
        if (!isset($postdata['ltv_max'])) $postdata['ltv_max'] = 100;
        if (!isset($postdata['loan_age_min'])) $postdata['loan_age_min'] = 0;
        if (!isset($postdata['loan_age_max'])) $postdata['loan_age_max'] = 10;
        
		if (!isset($postdata['limit'])) $postdata['limit'] = 10;
		if (!isset($postdata['offset'])) $postdata['offset'] = 'dev_test_data'; //just for test data
        
        $property_data = $em->getRepository('RefiBundle:Transactions')->filterProspects($postdata);
		
		$district = array();
		foreach($property_data as $val) {
			$val['prospect'] = $em->getRepository('RefiBundle:Transactions')->fetchProspectByTransactionsId($val['id']);
			if(isset($val['prospect'][0])) $val['prospect'] = $val['prospect'][0];
			$val['prospect']['prospectloan'] = $em->getRepository('RefiBundle:Transactions')->fetchLoanByTransactionsId($val['id']);
			
			$good = false;
			if(isset($val['prospect']['prospectloan'][0])) {
				$val['prospect']['prospectloan'] = $val['prospect']['prospectloan'][0];
				if(($val['price'] >= $postdata['property_value_min'] && $val['price'] <= $postdata['property_value_max']) ||
					($val['newprice'] >= $postdata['property_value_min'] && $val['newprice'] <= $postdata['property_value_max'])
				   ) {
					$good = true;
				} else {
					$good = false;
				}
				
				if($val['prospect']['prospectloan']['ltv'] >= $postdata['ltv_min'] && $val['prospect']['prospectloan']['ltv'] <= $postdata['ltv_max']) {
					$good = true;
				} else {
					$good = false;
				}
				
				$from = $val['prospect']['prospectloan']['loanDate'];
				$to = new \DateTime('today');
				$loan_age = $from->diff($to)->y;
				
				if($loan_age >= $postdata['loan_age_min'] && $loan_age <= $postdata['loan_age_max']) {
					$good = true;
				} else {
					$good = false;
				}
				
				if($good == true) $district[$val['districtcode']][$val['sector']][] = $val;
			}
		}
		
		$response = new Response(json_encode($district));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
	
	/*-------------------------------------------------/
	|	route: <domain>/api/filter/finance
	|	postdata:
	|		- xxxx [under development]
	--------------------------------------------------*/
	public function filterFinanceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postdata = $request->request->all();
        
        if (!isset($postdata['income_min'])) $postdata['income_min'] = 0;
        if (!isset($postdata['income_max'])) $postdata['income_max'] = 5000000;
        if (!isset($postdata['property_owned_min'])) $postdata['property_owned_min'] = 0;
        if (!isset($postdata['property_owned_max'])) $postdata['property_owned_max'] = 10;
        if (!isset($postdata['age_min'])) $postdata['age_min'] = 18;
        if (!isset($postdata['age_max'])) $postdata['age_max'] = 70;
		if (!isset($postdata['assets_min'])) $postdata['assets_min'] = 0;
        if (!isset($postdata['assets_max'])) $postdata['assets_max'] = 10000000;
		if (!isset($postdata['debt_min'])) $postdata['debt_min'] = 0;
        if (!isset($postdata['debt_max'])) $postdata['debt_max'] = 5000000;
		
		if (!isset($postdata['limit'])) $postdata['limit'] = 10;
		if (!isset($postdata['offset'])) $postdata['offset'] = 'dev_test_data'; //just for test data
        
        $property_data = $em->getRepository('RefiBundle:Transactions')->filterProspects($postdata);
		
		$district = array();
		foreach($property_data as $val) {
			$val['prospect'] = $em->getRepository('RefiBundle:Transactions')->fetchProspectByTransactionsId($val['id']);
			$val['prospectloan'] = $em->getRepository('RefiBundle:Transactions')->fetchLoanByTransactionsId($val['id']);
			
			$good = false;
			if(isset($val['prospectloan'][0])) {
				// if($val['prospectloan'][0]['ltv'] >= $postdata['ltv_min'] && $val['prospectloan'][0]['ltv'] <= $postdata['ltv_max']) {
					// $good = true;
				// } else {
					// $good = false;
				// }
				
				// $from = $val['prospectloan'][0]['loanDate'];
				// $to = new \DateTime('today');
				// $loan_age = $from->diff($to)->y;
				
				// if($loan_age >= $postdata['loan_age_min'] && $loan_age <= $postdata['loan_age_max']) {
					// $good = true;
				// } else {
					// $good = false;
				// }
				
				// if($good == true) $district[$val['districtcode']][$val['sector']][] = $val;
			}
		}
		
		$response = new Response(json_encode($district));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
	
	/*-------------------------------------------------/
	|	route: <domain>/api/shortlist/save
	|	postdata:
	|		- prospectlist : e.g. [1,2,3,4]
	--------------------------------------------------*/
    public function shortlistSaveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postdata = $request->request->all();
		
		$user = $this->get('security.context')->getToken()->getUser();
		$userId = $user->getId();
		
		if(isset($postdata['prospectlist'])) {
			$list_data = explode(',', $postdata['prospectlist']);
			$clientlist = $em->getRepository('RefiBundle:Clientlist')->findOneBy(array('clientId' => $userId));
			
			foreach($list_data as $val) {
				$prospectlist = new Prospectlist();
				$prospectlist->setClientlistId($clientlist->getId());
				$prospectlist->setProspectId($val);
				$prospectlist->setDateAssigned(new \DateTime('today'));
				$em->persist($prospectlist);
				$em->flush();
			}			
		}
		
		$msg = array('status' => 'ok');
		
		$response = new Response(json_encode($msg));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
	
	/*-------------------------------------------------/
	|	route: <domain>/api/shortlist/retrieve
	|	postdata:
	|		- xxxx
	--------------------------------------------------*/
	public function shortlistRetrieveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postdata = $request->request->all();
		
		$user = $this->get('security.context')->getToken()->getUser();
		$userId = $user->getId();
		
		$clientlist = $em->getRepository('RefiBundle:Clientlist')->findOneBy(array('clientId' => $userId));
		$prospectlist = $em->getRepository('RefiBundle:Prospectlist')->findBy(array('clientlistId' => $clientlist->getId()));
				
		foreach($prospectlist as $val) {
			$repository = $em
				->getRepository('RefiBundle:Prospect');
			
			$query = $repository->createQueryBuilder('p')
				->where('p.id = :pid')
				->setParameter('pid', $val->getProspectId())
				->getQuery();
			$prospect_list_temp = $query->getArrayResult();
			$prospect_list[] = (isset($prospect_list_temp[0]) ? $prospect_list_temp[0] : array());
		}
		
		$response = new Response(json_encode($prospect_list));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
