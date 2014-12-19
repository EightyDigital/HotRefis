<?php

namespace Eighty\RefiBundle\Controller;

use Eighty\RefiBundle\Entity\Sectorlist;
use Eighty\RefiBundle\Entity\Postalregion;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class ApplicationController extends Controller
{
	private function _getDefaultParams()
	{
		$em = $this->getDoctrine()->getManager();
		$usr = $this->get('security.context')->getToken()->getUser();
		$data = array();
		
		$data['id'] = $usr->getId();
		$data['name'] = $usr->getFullname();
		$data['credits'] = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($data['id']);
		$data['sectors_owned'] = count($em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($data['id']));
		$data['max_sectors'] = $this->container->getParameter('max_sectors');
		$data['credit_per_prospect'] = $this->container->getParameter('credit_per_prospect');
		
		return $data;
	}
	
    public function indexAction()
    {
		$data = $this->_getDefaultParams();
		
        return $this->render('RefiBundle:Application:index.html.twig',
			array(
				'data' => $data,
			)
		);
    }

    public function listAction()
    {
		$em = $this->getDoctrine()->getManager();
		$data = $this->_getDefaultParams();
		
		$paginator = $this->get('knp_paginator');
		$prospectlist = $em->getRepository('RefiBundle:Prospectlist')->getProspectListContactedEngaged($data['id']);
		
		$temp_prospect_list = array();
		foreach($prospectlist as $key => $val) {
			$temp_prospect_list[$key]['sector'] = $val['sector_name'];
			$temp_prospect_list[$key]['prospects'][] = array(
											'prospectId' => $val['prospectId'],
											'profession' => $val['profession'],
											'derivedIncome' => $val['derivedIncome'],
											'property_owned' => $val['property_owned'],
											'status' => $val['status'],
											'note' => $val['note']
										);
		}
		
		$prospect_list = array();
		foreach($temp_prospect_list as $key => $val) {
			$prospect_list[$key] = $val;
			
			$prospect_list[$key]['total_rows'] = $total_rows = count($val['prospects']);
			$prospect_list[$key]['current_max_row'] = $current_max_row = ($total_rows > 10) ? $this->get('request')->query->get('prospect_list_'.$key, 1) * 10 : $total_rows;
			$prospect_list[$key]['current_min_row'] = $current_min_row = ($total_rows > 10) ? $current_max_row - 9 : 1;
			
			$prospect_list[$key]['pagination'] = $pagination = $paginator->paginate(
				$val['prospects'],
				$this->get('request')->query->get('prospect_list_'.$key, 1),
				10,
				array('pageParameterName' => 'prospect_list_'.$key)
			);
		}
		
		return $this->render('RefiBundle:Application:list.html.twig',
			array(
				'data' => $data,
				'prospect_list' => $prospect_list,
			)
		);
    }

	public function prospectAction()
    {
		$data = $this->_getDefaultParams();
		
        return $this->render('RefiBundle:Application:prospect.html.twig',
			array(
				'data' => $data,
			)
		);
    }

	public function campaignAction()
    {
		$data = $this->_getDefaultParams();

        return $this->render('RefiBundle:Application:campaign.html.twig',
			array(
				'data' => $data,
			)
		);
    }

    public function calculatorAction()
    {
		$data = $this->_getDefaultParams();
		
		$session = new Session();
		print_r($session->get('prospect_ids')); exit();

        return $this->render('RefiBundle:Application:calculator.html.twig',
			array(
				'data' => $data,
			)
		);
    }

    public function reportAction()
    {
        $data = $this->_getDefaultParams();

        return $this->render('RefiBundle:Application:report.html.twig',
			array(
				'data' => $data,
			)
		);
    }

	/*-------------------------------------------------/
	|	route: <domain>/api/filter/property
	|	postdata: none;
	--------------------------------------------------*/
	public function filterPropertyAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$usr = $this->get('security.context')->getToken()->getUser();
		
		$postdata = $request->query->all();
        if (!isset($postdata['campaign'])) $postdata['campaign'] = false;
		
		if($postdata['campaign'] == true) {
			$property_data = $em->getRepository('RefiBundle:Transactions')->filterSectorsBySectorlistClientId($usr->getId());
		} else {
			$property_data = $em->getRepository('RefiBundle:Transactions')->filterSectors();
		}
		
		$sector_data = array();
		foreach($property_data as $property_data) {
			$sector_data[$property_data['sector']]['name'] = $property_data['name'];
			$sector_data[$property_data['sector']]['sector_code'] = $property_data['sector'];
			$sector_data[$property_data['sector']]['longitude'] = $property_data['longitude'];
			$sector_data[$property_data['sector']]['latitude'] = $property_data['latitude'];
			$sector_data[$property_data['sector']]['total_sector_prospects'] = $property_data['num_prospects']; 
		}
		
		$response = new Response(json_encode($sector_data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
	}
	
	/*-------------------------------------------------/
	|	route: <domain>/api/filter/prospect
	|	postdata:
	|		- property_value_min; property_value_max;
	|		- ltv_min; ltv_max;
	|		- loan_age_min; loan_age_max;
	|		- income_min; income_max;
	|		- property_owned_min; property_owned_max;
	|		- age_min; age_max;
	|		- assets_min; assets_max;
	|		- debt_min; debt_max;
	|		- certainty;
	|		- sector;
	--------------------------------------------------*/
    public function filterProspectAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$userId = $user->getId();
        $postdata = $request->query->all();

        if (!isset($postdata['property_value_min'])) $postdata['property_value_min'] = 0;
        if (!isset($postdata['property_value_max'])) $postdata['property_value_max'] = 10000000; //10000000;

        if (!isset($postdata['ltv_min'])) $postdata['ltv_min'] = 0;
        if (!isset($postdata['ltv_max'])) $postdata['ltv_max'] = 100; //100;

        if (!isset($postdata['loan_age_min'])) $postdata['loan_age_min'] = 0;
        if (!isset($postdata['loan_age_max'])) $postdata['loan_age_max'] = 10; //10;

		if (!isset($postdata['income_min'])) $postdata['income_min'] = 0;
        if (!isset($postdata['income_max'])) $postdata['income_max'] = 5000000; //5000000;

        if (!isset($postdata['property_owned_min'])) $postdata['property_owned_min'] = 1;
        if (!isset($postdata['property_owned_max'])) $postdata['property_owned_max'] = 10; //10;

        if (!isset($postdata['age_min'])) $postdata['age_min'] = 18; //18;
        if (!isset($postdata['age_max'])) $postdata['age_max'] = 70; //70;

		if (!isset($postdata['assets_min'])) $postdata['assets_min'] = 0;
        if (!isset($postdata['assets_max'])) $postdata['assets_max'] = 10000000; //10000000;

		if (!isset($postdata['debt_min'])) $postdata['debt_min'] = 0;
        if (!isset($postdata['debt_max'])) $postdata['debt_max'] = 5000000; //5000000;

		if (!isset($postdata['certainty'])) $postdata['certainty'] = 0;
		if (!isset($postdata['sector'])) $postdata['sector'] = 0;

		if ($postdata['sector'] == 0) {
			$property_data = $em->getRepository('RefiBundle:Transactions')->filterProspectsBySector(0, $userId);
		} else {
			$property_data = $em->getRepository('RefiBundle:Transactions')->filterProspectsBySector(1, $postdata['sector']);
		}
		
		$perfect_score = 100;
		$sector = array();
		foreach($property_data as $key => $val) {
			$score = 0;
			
			if($postdata['property_value_min'] == $postdata['property_value_max'] && ($val['average_price'] >= $postdata['property_value_min'] || $val['average_newprice'] >= $postdata['property_value_min'])) {
				$score++;
			} else {
				if(($val['average_price'] >= $postdata['property_value_min'] && $val['average_price'] <= $postdata['property_value_max']) || ($val['average_newprice'] >= $postdata['property_value_min'] && $val['average_newprice'] <= $postdata['property_value_max']))
					$score++;
			}

			if($postdata['ltv_min'] == $postdata['ltv_max'] && $val['average_ltv'] >= $postdata['ltv_min']) {
				$score++;
			} else {
				if($val['average_ltv'] >= $postdata['ltv_min'] && $val['average_ltv'] <= $postdata['ltv_max'])
					$score++;
			}

			if($postdata['loan_age_min'] == $postdata['loan_age_max'] && $val['average_loan_age'] >= $postdata['loan_age_min']) {
				$score++;
			} else {
				if($val['average_loan_age'] >= $postdata['loan_age_min'] && $val['average_loan_age'] <= $postdata['loan_age_max'])
					$score++;
			}

			if($postdata['income_min'] == $postdata['income_max'] && $val['average_income'] >= $postdata['income_min']) {
				$score++;
			} else {
				if($val['average_income'] >= $postdata['income_min'] && $val['average_income'] <= $postdata['income_max'])
					$score++;
			}

			if($postdata['property_owned_min'] == $postdata['property_owned_max'] && $val['average_assets_owned'] >= $postdata['property_owned_min']) {
				$score++;
			} else {
				if($val['average_assets_owned'] >= $postdata['property_owned_min'] && $val['average_assets_owned'] <= $postdata['property_owned_max'])
					$score++;
			}

			if($postdata['assets_min'] == $postdata['assets_max'] && ($val['average_assets_owned'] * $val['average_newprice']) >= $postdata['assets_min']) {
				$score++;
			} else {
				if(($val['average_assets_owned'] * $val['average_newprice']) >= $postdata['assets_min'] && ($val['average_assets_owned'] * $val['average_newprice']) <= $postdata['assets_max'])
					$score++; 
			}

			if($postdata['age_min'] == $postdata['age_max'] && $val['average_prospect_age'] >= $postdata['age_min']) {
				$score++;
			} else {
				if($val['average_prospect_age'] >= $postdata['age_min'] && $val['average_prospect_age'] <= $postdata['age_max'])
					$score++;
			}

			$debt = ($val['average_ltv'] / 100) * $val['average_price'];
			if($postdata['debt_min'] == $postdata['debt_max'] && $debt >= $postdata['debt_min']) {
				$score++;
			} else {
				if($debt >= $postdata['debt_min'] && $debt <= $postdata['debt_max'])
					$score++;
			}

			$temp_score = (int) (($score / 8) * 100);

			if(isset($temp[$val['urakey']]['num_prospects']))
				$temp[$val['urakey']]['num_prospects'] += 1;
			else
				$temp[$val['urakey']]['num_prospects'] = 1;

			if(isset($temp[$val['urakey']]['perfect_score'])) {
				if($temp_score >= $perfect_score) {
					$temp[$val['urakey']]['perfect_score']++;
				}
			} else {
				if($temp_score >= $perfect_score) {
					$temp[$val['urakey']]['perfect_score'] = 1;
				} else {
					$temp[$val['urakey']]['perfect_score'] = 0;
				}
			}

			$temp_property_score = round(($temp[$val['urakey']]['perfect_score'] / $temp[$val['urakey']]['num_prospects']) * 100, 0);

			if(isset($temp_sector_score[$val['sector']])) {
				$temp_sector_score[$val['sector']] += $temp_property_score;
			} else {
				$temp_sector_score[$val['sector']] = $temp_property_score;
			}

			$sector[$val['sector']]['name'] = !empty($val['sector_name']) ? $val['sector_name'] : "Temporary Sector Name";
			$sector[$val['sector']]['sector_code'] = $val['sector'];
			$sector[$val['sector']]['longitude'] = $val['pr_long'];
			$sector[$val['sector']]['latitude'] = $val['pr_lat'];
			$sector[$val['sector']]['sector_score'] = 0;
			$sector[$val['sector']]['total_sector_prospects'] = 0;

			$sector[$val['sector']]['properties'][$val['urakey']]['longitude'] = $val['longitude'];
			$sector[$val['sector']]['properties'][$val['urakey']]['latitude'] = $val['latitude'];
			$sector[$val['sector']]['properties'][$val['urakey']]['property_score'] = $temp_property_score;
			$sector[$val['sector']]['properties'][$val['urakey']]['total_property_prospects'] = $temp[$val['urakey']]['perfect_score'];

			if($temp_score >= $perfect_score) {
				$sector[$val['sector']]['properties'][$val['urakey']]['prospects'][]['prospect_id'] = $val['prospectId'];
			}
		}

		foreach($sector as $keys => $vals) {
			$temp_score = 0; $ctr = 0; $temp_prospects = 0;
			foreach($vals['properties'] as $keyp => $valp) {
				if($valp['property_score'] < $postdata['certainty'] || ($postdata['certainty'] == 0 && $valp['property_score'] <= $postdata['certainty'])) {
					unset($sector[$keys]['properties'][$keyp]);
				} else {
					$temp_prospects += $valp['total_property_prospects'];
					$temp_score++;
				}
				$ctr++;
			}
			$sector[$keys]['sector_score'] = round(($temp_score / $ctr) * 100, 0);
			$sector[$keys]['total_sector_prospects'] = $temp_prospects;
		}

		$response = new Response(json_encode($sector));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

	/*-------------------------------------------------/
	|	route: <domain>/api/shortlist/checkout
	|	postdata:
	|		- sectors : json_encode of filter API
	--------------------------------------------------*/
    public function shortlistCheckoutAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
        $postdata = $request->request->all();

		$user = $this->get('security.context')->getToken()->getUser();
		$userId = $user->getId();

		$status = 'fail';
		$message = 'Nothing to checkout.';
		
		if (!isset($postdata['sectors'])) $postdata['sectors'] = 0;
		
		if($postdata['sectors'] !== 0) {
			$sectors = json_decode($postdata['sectors']);
			
			$sector_list = $em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($userId);
			$temp_sectors = array();

			foreach($sector_list as $val) {
				$temp_sectors[] = $val['sectorCode'];
			}

			$sector_count = count($temp_sectors);
			$ctr = 0;

			if($sector_count >= 3) {
				$status = 'fail';
				$message = 'You already have 3 sectors in your list.';
			} else {
				foreach($sectors as $key => $sector) {
					if(($sector_count + $ctr) == 3) {
						break;
					} else {
						if(!in_array($sector->sector, $temp_sectors)) {
							$sectorlist = new Sectorlist();
							$sectorlist->setClientId($userId);
							$sectorlist->setSectorCode($sector->sector);
							$sectorlist->setDateadded(date('Y-m-d'));
							$sectorlist->setValidity($sector->validity);
							$em->persist($sectorlist);
							$em->flush();
							$em->clear();

							$ctr++;
						}
					}
				}

				$status = 'ok';
				$message = 'Checked out!';
			}
		}

		$msg = array('status' => $status, 'message' => $message);

		$response = new Response(json_encode($msg));
		if($status == 'fail') {
			$response->setStatusCode(Response::HTTP_BAD_REQUEST);
		} else {
			$response->setStatusCode(Response::HTTP_OK);
		}
		$response->headers->set('Content-Type', 'application/json');

        return $response;
	}
	
	/*-------------------------------------------------/
	|	route: <domain>/api/shortlist/blast
	|	postdata:
	|		- prospects : json_encode of filter API
	--------------------------------------------------*/
    public function shortlistBlastAction(Request $request)
    {
		$session = new Session();
		$postdata = $request->request->all();

		$status = 'fail';
		$message = 'Nothing to blast.';

		if (!isset($postdata['prospects'])) $postdata['prospects'] = 0;

		$prospect_ids = array();
		if($postdata['prospects'] !== 0) {
			$prospects = json_decode($postdata['prospects']);
		
			foreach($prospects as $object) {
				foreach($object as $sector) {
					foreach($sector->properties as $property) {
						foreach($property->prospects as $prospect) {
							$prospect_ids[] = $prospect->prospect_id;
						}
					}
				}
			}
			
			$session->set('prospect_ids', $prospect_ids);
			
			$status = 'ok';
			$message = 'Blasted!';
		}

		$msg = array('status' => $status, 'message' => $message);

		$response = new Response(json_encode($msg));
		if($status == 'fail') {
			$response->setStatusCode(Response::HTTP_BAD_REQUEST);
		} else {
			$response->setStatusCode(Response::HTTP_OK);
		}
        $response->headers->set('Content-Type', 'application/json');

        return $response;
	}
	
	/*-------------------------------------------------/
	|	route: <domain>/api/shortlist/calculator
	|	postdata:
	|		- calc_input_values : json_encode of calculator values
	--------------------------------------------------*/
    public function shortlistCalculatorAction(Request $request)
    {
		$session = new Session();
		$postdata = $request->request->all();

		$status = 'fail';
		$message = 'No formula inputted.';

		if (!isset($postdata['calculator_input'])) $postdata['calculator_input'] = 0;

		$prospect_ids = $session->get('prospect_ids');
		$calc_input_values = array();
		if($postdata['calculator_input'] !== 0 && !empty($prospect_ids)) {
			$calc_input_values = json_decode($postdata['calculator_input']);
		
			$session->set('calc_input_values', $calc_input_values);
			
			$status = 'ok';
			$message = 'Calculator ready!';
		}

		$msg = array('status' => $status, 'message' => $message);

		$response = new Response(json_encode($msg));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
	}

}
