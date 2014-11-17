<?php

namespace Eighty\RefiBundle\Controller;

use Eighty\RefiBundle\Entity\Prospectlist;
use Eighty\RefiBundle\Entity\Creditused;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\Session\Session;

class ApplicationController extends Controller
{
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager();
		$usr = $this->get('security.context')->getToken()->getUser();
		$id = $usr->getId();
		
		$credits = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($id);
		
        return $this->render('RefiBundle:Application:index.html.twig',
			array(
				'name' => $usr->getFullname(),
				'credits' => $credits,
			)
		);
    }

    public function addAction()
    {
        return $this->render('RefiBundle:Application:add.html.twig');
        //, array('name' => $name)
    }
    public function listAction()
    {
		$em = $this->getDoctrine()->getManager();
		$usr = $this->get('security.context')->getToken()->getUser();
		$id = $usr->getId();
		$credits = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($id);
		
		$prospect_list = $em->getRepository('RefiBundle:Prospectlist')->getProspectList($id);
		foreach($prospect_list as $key => $val) {
			$property_owned = $em->getRepository('RefiBundle:Transactions')->fetchAssetsByProspectId($val['prospectId']);
			$prospect_list[$key]['property_owned'] = (isset($property_owned[0]['count_tid']) ? $property_owned[0]['count_tid'] : 0);
			
			$quarter = $val['derivedIncome'] * 0.25;
			$min = round(($val['derivedIncome'] - $quarter), 0);
			$max = round(($val['derivedIncome'] + $quarter), 0);
			$prospect_list[$key]['income_range'] = "$" . number_format(round($min, (0 - (strlen((string) $min) - 1)))) . " - " . "$" . number_format(round($max, (0 - (strlen((string) $max) - 1))));
			
			$prospect_list[$key]['index'] = $key + 1;
			
		}
		
		$total_rows = count($prospect_list);
		$current_max_row = ($total_rows > 50) ? $this->get('request')->query->get('prospect_list', 1) * 50 : $total_rows;
		$current_min_row = ($total_rows > 50) ? $current_max_row - 49 : 1;
		
		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$prospect_list, 
			$this->get('request')->query->get('prospect_list', 1), 
			50,
			array('pageParameterName' => 'prospect_list')
		);
		
		return $this->render('RefiBundle:Application:list.html.twig',
			array(
				'name' => $usr->getFullname(),
				'credits' => $credits,
				'prospect_list' => $pagination,
				'total_rows' => $total_rows,
				'current_max_row' => $current_max_row,
				'current_min_row' => $current_min_row,
			)
		);
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
		//$session = new Session();
		//$loaded_properties = array();
		
		//if($session->has('loaded_properties'))
		//	$loaded_properties = $session->get('loaded_properties');

		$em = $this->getDoctrine()->getManager();
        $postdata = $request->query->all();
        
        if (!isset($postdata['property_value_min'])) $postdata['property_value_min'] = 0;
        if (!isset($postdata['property_value_max'])) $postdata['property_value_max'] = 10000000;
				
        if (!isset($postdata['ltv_min'])) $postdata['ltv_min'] = 0;
        if (!isset($postdata['ltv_max'])) $postdata['ltv_max'] = 100;
				
        if (!isset($postdata['loan_age_min'])) $postdata['loan_age_min'] = 0;
        if (!isset($postdata['loan_age_max'])) $postdata['loan_age_max'] = 10;
		
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
		        
		if (!isset($postdata['limit'])) $postdata['limit'] = 15;
		if (!isset($postdata['offset'])) $postdata['offset'] = 1;
		
        // $property_data = $em->getRepository('RefiBundle:Transactions')->filterProspects($postdata, $loaded_properties);
		$property_data = $em->getRepository('RefiBundle:Transactions')->filterProspects($postdata);
		
		// $district = array();
		$sector = array();
		$temp_data = array();
		foreach($property_data as $val) {
			$val['newprice'] = round($val['newprice'], 2);
			$val['prospect'] = $em->getRepository('RefiBundle:Transactions')->fetchProspectByTransactionsId($val['id']);
			$val['prospect'] = $val['prospect'][0];
			
			$val_prospect_id = $val['prospect']['id'];
			
			$val['prospect']['prospectloan'] = $em->getRepository('RefiBundle:Transactions')->fetchLoanByTransactionsAndProspectId($val['id'], $val_prospect_id);
			$val['prospect']['prospectloan'] = $val['prospect']['prospectloan'][0];
			
			$score = 0;
				
			if(($val['price'] >= $postdata['property_value_min'] && $val['price'] <= $postdata['property_value_max']) || ($val['newprice'] >= $postdata['property_value_min'] && $val['newprice'] <= $postdata['property_value_max']))
				$score++;
			
			if($val['prospect']['prospectloan']['ltv'] >= $postdata['ltv_min'] && $val['prospect']['prospectloan']['ltv'] <= $postdata['ltv_max'])
				$score++;
			
			$from = $val['prospect']['prospectloan']['loanDate'];
			$to = new \DateTime('today');
			$loan_age = $from->diff($to)->y;
			
			if($loan_age >= $postdata['loan_age_min'] && $loan_age <= $postdata['loan_age_max'])
				$score++;
							
			if($val['prospect']['derivedIncome'] >= $postdata['income_min'] && $val['prospect']['derivedIncome'] <= $postdata['income_max'])
				$score++;
			
			$property_owned = $em->getRepository('RefiBundle:Transactions')->fetchAssetsByProspectId($val_prospect_id);
			if($property_owned[0]['count_tid'] >= $postdata['property_owned_min'] && $property_owned[0]['count_tid'] <= $postdata['property_owned_max'])
				$score++;
			if($property_owned[0]['sum_nprice'] >= $postdata['assets_min'] && $property_owned[0]['sum_nprice'] <= $postdata['assets_max'])
				$score++;
			
			if($val['prospect']['age'] >= $postdata['age_min'] && $val['prospect']['age'] <= $postdata['age_max'])
				$score++;
			
			$debt = ($val['prospect']['prospectloan']['ltv'] / 100) * $val['price'];
			if($debt >= $postdata['debt_min'] && $debt <= $postdata['debt_max'])
				$score++;
			
			$temp_data['prospect']['prospect_id'] = $val['prospect']['id'];
			$temp_data['prospect']['prospect_score'] = (int) (($score / 8) * 100);
									
			//newer implementation
			$sector[$val['sector']]['name'] = "Temporary Sector Name";
			$sector[$val['sector']]['longitude'] = $val['longitude'];
			$sector[$val['sector']]['latitude'] = $val['latitude'];
			$sector[$val['sector']]['sector_score'] = 100;
			$sector[$val['sector']]['total_prospects'] = 345;
			$sector[$val['sector']]['properties'][$val['urakey']]['property_score'] = 100;
			$sector[$val['sector']]['properties'][$val['urakey']][] = $temp_data['prospect'];
			
			//new implementation
			// $val['prospect']['heatmap_score'] = (int) (($score / 8) * 100);
			// $district[$val['districtcode']]['longitude'] = $val['longitude'];
			// $district[$val['districtcode']]['latitude'] = $val['latitude'];
			// $district[$val['districtcode']][$val['urakey']][] = $val['prospect'];
			
			// $district[$val['districtcode']][$val['sector']][] = $val;
			// $district[$val['districtcode']]['longitude'] = $val['longitude'];
			// $district[$val['districtcode']]['latitude'] = $val['latitude'];
			// $loaded_properties[] = $val['id'];
		}
		
		// $session->set('loaded_properties', $loaded_properties);
		
		$response = new Response(json_encode($sector));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
		
	/*-------------------------------------------------/
	|	route: <domain>/api/shortlist/save
	|	postdata:
	|		- prospectlist : e.g. {"546678":100,"546679":66}
	--------------------------------------------------*/
    public function shortlistSaveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postdata = $request->request->all();
		
		$user = $this->get('security.context')->getToken()->getUser();
		$userId = $user->getId();
		
		$status = 'fail';
		$message = 'Nothing to checkout.';
		
		if(isset($postdata['prospectlist'])) {
			$list_data = json_decode(stripslashes($_POST['prospectlist']));
			$clientlist = $em->getRepository('RefiBundle:Clientlist')->findOneBy(array('clientId' => $userId));
			
			$credits = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($userId);
			$need_credits = count($list_data) * 3;
			
			if(($credits - $need_credits) >= 0) {
				foreach($list_data as $key => $val) {
					$prospectlist = new Prospectlist();
					$prospectlist->setClientlistId($clientlist->getId());
					$prospectlist->setProspectId($key);
					$prospectlist->setScore($val);
					$prospectlist->setDateAssigned(new \DateTime('today'));
					$em->persist($prospectlist);
					$em->flush();
					
					$creditused = new Creditused();
					$creditused->setClientId($userId);
					$creditused->setDate(new \DateTime('today'));
					$creditused->setCreditUsed(3);
					$em->persist($creditused);
					$em->flush();
				}
				$status = 'ok';
				$message = 'Checked out!';
			} else {
				$status = 'fail';
				$message = 'Not enough credits!';
			}				
		}
		
		$msg = array('status' => $status, 'message' => $message);
		
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
