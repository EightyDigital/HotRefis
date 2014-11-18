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
		        
		$property_data = $em->getRepository('RefiBundle:Transactions')->filterSectors();
		
		$sector = array();
		foreach($property_data as $val) {
			$score = 0;
				
			if(($val['average_price'] >= $postdata['property_value_min'] && $val['average_price'] <= $postdata['property_value_max']) || ($val['average_newprice'] >= $postdata['property_value_min'] && $val['average_newprice'] <= $postdata['property_value_max']))
				$score++;
			
			if($val['average_ltv'] >= $postdata['ltv_min'] && $val['average_ltv'] <= $postdata['ltv_max'])
				$score++;
			
			if($val['average_loan_age'] >= $postdata['loan_age_min'] && $val['average_loan_age'] <= $postdata['loan_age_max'])
				$score++;
							
			if($val['average_income'] >= $postdata['income_min'] && $val['average_income'] <= $postdata['income_max'])
				$score++;
			
			if(isset($temp[$val['sector']]['num_prospects']))
				$temp[$val['sector']]['num_prospects'] += $val['num_prospects'];
			else
				$temp[$val['sector']]['num_prospects'] = $val['num_prospects'];
			
			if(isset($temp[$val['sector']]['count']))
				$temp[$val['sector']]['count']++;
			else
				$temp[$val['sector']]['count'] = 1;
				
			$temp[$val['sector']]['avg_properties_owned'] = ($temp[$val['sector']]['count'] / $temp[$val['sector']]['num_prospects']) * 100;
			if($temp[$val['sector']]['avg_properties_owned'] >= $postdata['property_owned_min'] && $temp[$val['sector']]['avg_properties_owned'] <= $postdata['property_owned_max'])
				$score++;
			if(($temp[$val['sector']]['avg_properties_owned'] * $val['average_newprice']) >= $postdata['assets_min'] && ($temp[$val['sector']]['avg_properties_owned'] * $val['average_newprice']) <= $postdata['assets_max'])
				$score++;
			
			if($val['average_prospect_age'] >= $postdata['age_min'] && $val['average_prospect_age'] <= $postdata['age_max'])
				$score++;
			
			$debt = ($val['average_ltv'] / 100) * $val['average_price'];
			if($debt >= $postdata['debt_min'] && $debt <= $postdata['debt_max'])
				$score++;
			
			if(isset($temp[$val['sector']]['score']))
				$temp[$val['sector']]['score'] += (int) (($score / 8) * 100);
			else
				$temp[$val['sector']]['score'] = (int) (($score / 8) * 100);
						
			$sector[$val['sector']]['name'] = "Temporary Sector Name";
			$sector[$val['sector']]['sector_code'] = $val['sector'];
			$sector[$val['sector']]['longitude'] = $val['pr_long'];
			$sector[$val['sector']]['latitude'] = $val['pr_lat'];
			$sector[$val['sector']]['sector_score'] = round($temp[$val['sector']]['score'] / $temp[$val['sector']]['count'], 0);
			$sector[$val['sector']]['total_sector_prospects'] = $temp[$val['sector']]['num_prospects'];
			
			$sector[$val['sector']]['3_months'] = round($sector[$val['sector']]['total_sector_prospects'] * 3, 2);
			$sector[$val['sector']]['6_months'] = round($sector[$val['sector']]['total_sector_prospects'] * 2.5, 2);
			$sector[$val['sector']]['12_months'] = round($sector[$val['sector']]['total_sector_prospects'] * 2, 2);
			
			$sector[$val['sector']]['checked_out'] = 0;
			
			$sector[$val['sector']]['properties'][$val['urakey']]['longitude'] = $val['longitude'];
			$sector[$val['sector']]['properties'][$val['urakey']]['latitude'] = $val['latitude'];
			$sector[$val['sector']]['properties'][$val['urakey']]['property_score'] = (int) (($score / 8) * 100);
			$sector[$val['sector']]['properties'][$val['urakey']]['total_property_prospects'] = $val['num_prospects'];
		}
		
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
