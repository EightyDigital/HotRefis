<?php

namespace Eighty\RefiBundle\Controller;

use Eighty\RefiBundle\Entity\Prospectlist;
use Eighty\RefiBundle\Entity\Creditused;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Response;


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
		$paginator = $this->get('knp_paginator');
			
		$credits = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($id);
		
		$condo = $em->getRepository('RefiBundle:Prospectlist')->getUrakeyByClient($id);
		$prospect_list = array();
		foreach($condo as $ckey => $val) {
			$prospect_list[$ckey]['condo'] = $val['urakey'];
			$prospect_list[$ckey]['prospects'] = $em->getRepository('RefiBundle:Prospectlist')->getProspectList($id, $val['urakey']);
			foreach($prospect_list[$ckey]['prospects'] as $key => $pval) {
				$quarter = $pval['derivedIncome'] * 0.25;
				$min = round(($pval['derivedIncome'] - $quarter), 0);
				$max = round(($pval['derivedIncome'] + $quarter), 0);
				$prospect_list[$ckey]['prospects'][$key]['income_range'] = "$" . number_format(round($min, (0 - (strlen((string) $min) - 1)))) . " - " . "$" . number_format(round($max, (0 - (strlen((string) $max) - 1))));
				
				$prospect_list[$ckey]['prospects'][$key]['index'] = $key + 1;
			}
			
			$prospect_list[$ckey]['total_rows'] = $total_rows = count($prospect_list[$ckey]['prospects']);
			$prospect_list[$ckey]['current_max_row'] = $current_max_row = ($total_rows > 10) ? $this->get('request')->query->get('prospect_list_'.$ckey, 1) * 10 : $total_rows;
			$prospect_list[$ckey]['current_min_row'] = $current_min_row = ($total_rows > 10) ? $current_max_row - 9 : 1;
			
			$prospect_list[$ckey]['pagination'] = $pagination = $paginator->paginate(
				$prospect_list[$ckey]['prospects'], 
				$this->get('request')->query->get('prospect_list_'.$ckey, 1), 
				10,
				array('pageParameterName' => 'prospect_list_'.$ckey)
			);
			
		}
		
		return $this->render('RefiBundle:Application:list.html.twig',
			array(
				'name' => $usr->getFullname(),
				'credits' => $credits,
				'prospect_list' => $prospect_list,
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
	|		- income_min; income_max;
	|		- property_owned_min; property_owned_max;
	|		- age_min; age_max;
	|		- assets_min; assets_max;
	|		- debt_min; debt_max;
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
						
			$sector[$val['sector']]['name'] = !empty($val['sector_name']) ? $val['sector_name'] : "Temporary Sector Name";
			$sector[$val['sector']]['sector_code'] = $val['sector'];
			$sector[$val['sector']]['longitude'] = $val['pr_long'];
			$sector[$val['sector']]['latitude'] = $val['pr_lat'];
			$sector[$val['sector']]['sector_score'] = round($temp[$val['sector']]['score'] / $temp[$val['sector']]['count'], 0);
			$sector[$val['sector']]['total_sector_prospects'] = $temp[$val['sector']]['num_prospects'];
			
			$sector[$val['sector']]['3_months'] = round($sector[$val['sector']]['total_sector_prospects'] * 3, 0);
			$sector[$val['sector']]['6_months'] = round($sector[$val['sector']]['total_sector_prospects'] * 2.5, 0);
			$sector[$val['sector']]['12_months'] = round($sector[$val['sector']]['total_sector_prospects'] * 2, 0);
			
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
	|	route: <domain>/api/shortlist/checkout
	|	postdata:
	|		- prospectlist : json_encode of filter API
	--------------------------------------------------*/
    public function shortlistCheckoutAction(Request $request)
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
			
			$need_credits = count($list_data) * 3; //temporary
			$batchSize = 20;
			
			if(($credits - $need_credits) >= 0) {
				foreach($list_data as $key => $val) {
					foreach($val->properties as $pkey => $pval) {
						$prospect_ids = $em->getRepository('RefiBundle:Prospect')->fetchProspectIdsByUrakeyAndSector($pkey, $val->sector_code);
						$i = 1;
						foreach($prospect_ids as $prospect_val) {
							$prospectlist = new Prospectlist();
							$prospectlist->setClientlistId($clientlist->getId());
							$prospectlist->setProspectId($prospect_val['prospectId']);
							$prospectlist->setUrakey($pkey);
							$prospectlist->setScore($pval->property_score);
							$prospectlist->setDateAssigned(new \DateTime('today'));
							$prospectlist->setEngaged(0);
							$prospectlist->setStatus(0);
							$em->persist($prospectlist);
							
							if (($i % $batchSize) == 0) {
								$em->flush();
								$em->clear();
							}
							
							$i++;
						}
					}
					
					// $creditused = new Creditused();
					// $creditused->setClientId($userId);
					// $creditused->setDate(new \DateTime('today'));
					// $creditused->setCreditUsed(3);
					// $em->persist($creditused);
					// $em->flush();
				}
				
				$em->flush();
				$em->clear();
								
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
	
}
