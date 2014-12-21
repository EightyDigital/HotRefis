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
	private $_num_div = array(
		100000000, 10000000, 1000000, 
		100000, 10000, 1000, 100, 10, 1
	);
	
	private function _getDefaultParams()
	{
		$em = $this->getDoctrine()->getManager();
		$usr = $this->get('security.context')->getToken()->getUser();
		$data = array();
		
		$data['id'] = $usr->getId();
		$data['name'] = $usr->getFullname();
		$data['email'] = $usr->getEmail();
		$data['address'] = $usr->getAddress();
		$data['company'] = $usr->getAgency();
		
		$data['credits'] = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($data['id']);
		$data['sectors_owned'] = count($em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($data['id']));
		$data['max_sectors'] = $this->container->getParameter('max_sectors');
		$data['credit_per_prospect'] = $this->container->getParameter('credit_per_prospect');
		
		return $data;
	}
	
	private function _getReportFormula($calc_input_values, $propertydata, $loandata)
	{
		$formula = array();
		
		$months = $calc_input_values['loan_term'] * 12;
		
		$formula['initial_loan_amount']			= $calc_input_values['ltv_at_purchase'] * $propertydata->getPrice();
		$formula['equity_at_mortgage_rate'] 	= $propertydata->getPrice() - $formula['initial_loan_amount'];
		
		$formula['years_since_loan_taken'] 		= (date("Y") - $loandata->getLoanDate()->format("Y")) + (1 - ($loandata->getLoanDate()->format("n") / 12));
		$formula['months_since_loan_taken'] 	= $formula['years_since_loan_taken'] * 12;
		$formula['loan_term_years_remaining']	= round($calc_input_values['loan_term'] - $formula['years_since_loan_taken'], 1);
		
		$formula['rate_per_month'] 				= $calc_input_values['existing_loan_mortgage_rate'] / 12;
		$formula['monthly_payment'] 			= ($formula['rate_per_month'] * $formula['initial_loan_amount']) / (1 - pow(1 + $formula['rate_per_month'], -$months));
		$formula['principal_remaining'] 		= round(($formula['initial_loan_amount'] * pow(1 + $formula['rate_per_month'], $formula['months_since_loan_taken'])) - ($formula['monthly_payment'] * ((pow(1 + $formula['rate_per_month'], $formula['months_since_loan_taken']) - 1) / $formula['rate_per_month'])));
		$formula['principal_reduced_by'] 		= $formula['initial_loan_amount'] - $formula['principal_remaining'];
		$formula['total_payments'] 				= $formula['monthly_payment'] * $formula['months_since_loan_taken'];
		$formula['interest_paid'] 				= $formula['total_payments'] - $formula['principal_reduced_by'];
		$formula['principal_paid_deposit'] 		= $formula['equity_at_mortgage_rate'] + $formula['principal_reduced_by'];
		$formula['equity'] 						= $propertydata->getNewPrice() - $formula['principal_remaining'];
		$formula['ltv_new'] 					= round(($formula['principal_remaining'] / $propertydata->getNewPrice()) * 100);
		
		$formula['remaining_loan_months'] 		= round($formula['loan_term_years_remaining'] * 12);
		
		$formula['current_r']					= round(round(($calc_input_values['existing_loan_mortgage_rate'] * 100) / 12, 2) / 100, 10);
		
		$formula['current_r_1']					= round(round($calc_input_values['current_first_year'] / 12, 2) / 100, 10);
		$formula['current_r_2']					= round(round($calc_input_values['current_second_year'] / 12, 2) / 100, 10);
		$formula['current_r_3']					= round(round($calc_input_values['current_third_year'] / 12, 2) / 100, 10);
		$formula['current_r_4']					= round(round($calc_input_values['current_fourth_year'] / 12, 2) / 100, 10);
		$formula['current_r_5']					= round(round($calc_input_values['current_fifth_year'] / 12, 2) / 100, 10);
		$formula['current_r_6']					= round(round($calc_input_values['current_onwards'] / 12, 2) / 100, 10);
		
		$formula['refi_r_1']					= round(round($calc_input_values['refi_first_year'] / 12, 2) / 100, 10);
		$formula['refi_r_2']					= round(round($calc_input_values['refi_second_year'] / 12, 2) / 100, 10);
		$formula['refi_r_3']					= round(round($calc_input_values['refi_third_year'] / 12, 2) / 100, 10);
		$formula['refi_r_4']					= round(round($calc_input_values['refi_fourth_year'] / 12, 2) / 100, 10);
		$formula['refi_r_5']					= round(round($calc_input_values['refi_fifth_year'] / 12, 2) / 100, 10);
		$formula['refi_r_6']					= round(round($calc_input_values['refi_onwards'] / 12, 2) / 100, 10);
		
		$x = 1;
		while ($x <= 30) {
			// current_mortgage_scenario MODEL //
			
			switch($x) {
				case 1:
					$cur_formula_r = $formula['current_r_1'];
					break;
				case 2:
					$cur_formula_r = $formula['current_r_2'];
					break;
				case 3:
					$cur_formula_r = $formula['current_r_3'];
					break;
				case 4:
					$cur_formula_r = $formula['current_r_4'];
					break;
				case 5:
					$cur_formula_r = $formula['current_r_5'];
					break;
				default:
					$cur_formula_r = $formula['current_r_6'];
					break;
			}			
			
			$formula['current_mortgage_scenario'][$x]['monthly_payment'] = ($cur_formula_r * $formula['principal_remaining']) / (1 - pow(1 + $cur_formula_r, -$formula['remaining_loan_months']));
			$formula['current_mortgage_scenario'][$x]['yearly_payment'] = $formula['current_mortgage_scenario'][$x]['monthly_payment'] * 12;
			
			if($x == 1) {
				$formula['current_mortgage_scenario'][$x]['principal_remaining'] = ($formula['principal_remaining'] * pow(1 + $cur_formula_r, 12)) - ($formula['current_mortgage_scenario'][$x]['monthly_payment'] * ((pow(1 + $cur_formula_r,12) - 1) / $cur_formula_r));
			} else {
				$formula['current_mortgage_scenario'][$x]['principal_remaining'] = ($formula['current_mortgage_scenario'][$x - 1]['principal_remaining'] * pow(1 + $cur_formula_r, 12)) - ($formula['current_mortgage_scenario'][$x]['monthly_payment'] * ((pow(1 + $cur_formula_r,12) - 1) / $cur_formula_r));
			}
			
			if($formula['current_mortgage_scenario'][$x]['principal_remaining'] < 1) $formula['current_mortgage_scenario'][$x]['principal_remaining'] = 0;
			
			if($x == 1) {
				$formula['current_mortgage_scenario'][$x]['cumulative_principal_repaid'] = $formula['principal_remaining'] - $formula['current_mortgage_scenario'][$x]['principal_remaining'];
			} else {
				if($formula['current_mortgage_scenario'][$x - 1]['principal_remaining'] <= 0) {
					$formula['current_mortgage_scenario'][$x]['cumulative_principal_repaid'] = 0;
				} else {
					$formula['current_mortgage_scenario'][$x]['cumulative_principal_repaid'] = $formula['principal_remaining'] - $formula['current_mortgage_scenario'][$x]['principal_remaining'];
				}
			}
			
			if($x == 1) {
				$formula['current_mortgage_scenario'][$x]['principal_reduced_pa'] = $formula['current_mortgage_scenario'][$x]['cumulative_principal_repaid'];
			} else {
				if($formula['current_mortgage_scenario'][$x]['cumulative_principal_repaid'] <= 0) {
					$formula['current_mortgage_scenario'][$x]['principal_reduced_pa'] = 0;
				} else {
					$formula['current_mortgage_scenario'][$x]['principal_reduced_pa'] = $formula['current_mortgage_scenario'][$x]['cumulative_principal_repaid'] - $formula['current_mortgage_scenario'][$x - 1]['cumulative_principal_repaid'];
				}
			}
			
			if($formula['current_mortgage_scenario'][$x]['principal_reduced_pa'] <= 0) {
				$formula['current_mortgage_scenario'][$x]['interest_cost_pa'] = 0;
			} else {
				$formula['current_mortgage_scenario'][$x]['interest_cost_pa'] = ($formula['current_mortgage_scenario'][$x]['monthly_payment'] * 12) - $formula['current_mortgage_scenario'][$x]['principal_reduced_pa'];
			}
			
			if($x == 1) {
				$formula['current_mortgage_scenario'][$x]['cumulative_interest'] = $formula['current_mortgage_scenario'][$x]['interest_cost_pa'];
			} else {
				if($formula['current_mortgage_scenario'][$x]['interest_cost_pa'] <= 0) {
					$formula['current_mortgage_scenario'][$x]['cumulative_interest'] = 0;
				} else {
					$formula['current_mortgage_scenario'][$x]['cumulative_interest'] = $formula['current_mortgage_scenario'][$x]['interest_cost_pa'] + $formula['current_mortgage_scenario'][$x - 1]['cumulative_interest'];
				}
			}
			
			// REFI MODEL //
			
			switch($x) {
				case 1:
					$ref_formula_r = $formula['refi_r_1'];
					break;
				case 2:
					$ref_formula_r = $formula['refi_r_2'];
					break;
				case 3:
					$ref_formula_r = $formula['refi_r_3'];
					break;
				case 4:
					$ref_formula_r = $formula['refi_r_4'];
					break;
				case 5:
					$ref_formula_r = $formula['refi_r_5'];
					break;
				default:
					$ref_formula_r = $formula['refi_r_6'];
					break;
			}
			
			
			$formula['refi_scenario'][$x]['monthly_payment'] = ($ref_formula_r * $formula['principal_remaining']) / (1 - pow(1 + $ref_formula_r, -$formula['remaining_loan_months']));
			$formula['refi_scenario'][$x]['yearly_payment'] = $formula['refi_scenario'][$x]['monthly_payment'] * 12;
			
			if($x == 1) {
				$formula['refi_scenario'][$x]['principal_remaining'] = ($formula['principal_remaining'] * pow(1 + $ref_formula_r, 12)) - ($formula['refi_scenario'][$x]['monthly_payment'] * ((pow(1 + $ref_formula_r,12) - 1) / $ref_formula_r));
			} else {
				$formula['refi_scenario'][$x]['principal_remaining'] = ($formula['refi_scenario'][$x - 1]['principal_remaining'] * pow(1 + $ref_formula_r, 12)) - ($formula['refi_scenario'][$x]['monthly_payment'] * ((pow(1 + $ref_formula_r,12) - 1) / $ref_formula_r));
			}
			
			if($formula['refi_scenario'][$x]['principal_remaining'] < 1) $formula['refi_scenario'][$x]['principal_remaining'] = 0;
			
			if($x == 1) {
				$formula['refi_scenario'][$x]['cumulative_principal_repaid'] = $formula['principal_remaining'] - $formula['refi_scenario'][$x]['principal_remaining'];
			} else {
				if($formula['refi_scenario'][$x - 1]['principal_remaining'] <= 0) {
					$formula['refi_scenario'][$x]['cumulative_principal_repaid'] = 0;
				} else {
					$formula['refi_scenario'][$x]['cumulative_principal_repaid'] = $formula['principal_remaining'] - $formula['refi_scenario'][$x]['principal_remaining'];
				}
			}
			
			if($x == 1) {
				$formula['refi_scenario'][$x]['principal_reduced_pa'] = $formula['refi_scenario'][$x]['cumulative_principal_repaid'];
			} else {
				if($formula['refi_scenario'][$x]['cumulative_principal_repaid'] <= 0) {
					$formula['refi_scenario'][$x]['principal_reduced_pa'] = 0;
				} else {
					$formula['refi_scenario'][$x]['principal_reduced_pa'] = $formula['refi_scenario'][$x]['cumulative_principal_repaid'] - $formula['refi_scenario'][$x - 1]['cumulative_principal_repaid'];
				}
			}
			
			if($formula['refi_scenario'][$x]['principal_reduced_pa'] <= 0) {
				$formula['refi_scenario'][$x]['interest_cost_pa'] = 0;
			} else {
				$formula['refi_scenario'][$x]['interest_cost_pa'] = ($formula['refi_scenario'][$x]['monthly_payment'] * 12) - $formula['refi_scenario'][$x]['principal_reduced_pa'];
			}
			
			if($x == 1) {
				$formula['refi_scenario'][$x]['cumulative_interest'] = $formula['refi_scenario'][$x]['interest_cost_pa'];
			} else {
				if($formula['refi_scenario'][$x]['interest_cost_pa'] <= 0) {
					$formula['refi_scenario'][$x]['cumulative_interest'] = 0;
				} else {
					$formula['refi_scenario'][$x]['cumulative_interest'] = $formula['refi_scenario'][$x]['interest_cost_pa'] + $formula['refi_scenario'][$x - 1]['cumulative_interest'];
				}
			}
			
			$x++;
		}
		
		return $formula;
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

	public function campaignAction()
    {
		$data = $this->_getDefaultParams();

        return $this->render('RefiBundle:Application:campaign.html.twig',
			array(
				'data' => $data,
			)
		);
    }

    public function calculatorAction(Request $request)
    {
		$data = $this->_getDefaultParams();
		$em = $this->getDoctrine()->getManager();
		
		$session = new Session();
		
		if($session->has('prospect_ids')) {
			$postdata = $request->request->all();
			if (!isset($postdata['ltv_at_purchase'])) $postdata['ltv_at_purchase'] = 0;
			if (!isset($postdata['loan_term'])) $postdata['loan_term'] = 0;
			if (!isset($postdata['existing_loan_mortgage_rate'])) $postdata['existing_loan_mortgage_rate'] = 0;
			
			if($postdata['ltv_at_purchase'] !== 0 && $postdata['loan_term'] !== 0 && $postdata['existing_loan_mortgage_rate'] !== 0) {
				$postdata['ltv_at_purchase'] = $postdata['ltv_at_purchase'] / 100;
				$postdata['existing_loan_mortgage_rate'] = $postdata['existing_loan_mortgage_rate'] / 100;
				
				if ($postdata['current_first_year'] == '') $postdata['current_first_year'] = '3';
				if ($postdata['current_second_year'] == '') $postdata['current_second_year'] = '3';
				if ($postdata['current_third_year'] == '') $postdata['current_third_year'] = '4';
				if ($postdata['current_fourth_year'] == '') $postdata['current_fourth_year'] = '4';
				if ($postdata['current_fifth_year'] == '') $postdata['current_fifth_year'] = '5';
				if ($postdata['current_onwards'] == '') $postdata['current_onwards'] = '5';
				
				if ($postdata['refi_first_year'] == '') $postdata['refi_first_year'] = '2';
				if ($postdata['refi_second_year'] == '') $postdata['refi_second_year'] = '2';
				if ($postdata['refi_third_year'] == '') $postdata['refi_third_year'] = '3';
				if ($postdata['refi_fourth_year'] == '') $postdata['refi_fourth_year'] = '3';
				if ($postdata['refi_fifth_year'] == '') $postdata['refi_fifth_year'] = '4';
				if ($postdata['refi_onwards'] == '') $postdata['refi_onwards'] = '4';
				
				$session->set('calc_input_values', $postdata);
				
				$prospect_ids = $session->get('prospect_ids');
				$temp = $em->getRepository('RefiBundle:Transactions')->fetchLoansByProspectIds(implode(',', $prospect_ids));
				
				foreach($temp as $transactions) {
					$prospect_properties[] = $transactions['transactionId'];
				}
				$session->set('prospect_properties', $prospect_properties);
				
				if ($session->has('prospect_properties')) {
					$prospect_properties = $session->get('prospect_properties');
					if(!isset($prospect_properties[0])) {
						$prospect_properties[0] = 0;
					}
				} else {
					$prospect_properties[0] = 0;
				}			
				
				return $this->redirect($this->generateUrl('refi_report', array('id' => $prospect_properties[0])));
			}
			
			return $this->render('RefiBundle:Application:calculator.html.twig',
				array(
					'data' => $data,
				)
			);
		} else {
			return $this->render('RefiBundle:Application:calculator.html.twig',
				array(
					'data' => $data,
					'its_empty' => true,
				)
			);
		}
	}

    public function reportAction($id, Request $request)
    {
        $data = $this->_getDefaultParams();
		$em = $this->getDoctrine()->getManager();
		
		$session = new Session();
		$postdata = $request->query->all();
		$rdata = array();
		
		if(!empty($postdata)) { print_r($postdata); exit(); }
		
		$loandata = $em->getRepository('RefiBundle:Prospectloan')->findOneByTransactionId($id);
		$propertydata = $em->getRepository('RefiBundle:Transactions')->findOneById($id);
		
		if(!empty($loandata) && !empty($propertydata) && $session->has('calc_input_values')) {
			$calc_input_values = $session->get('calc_input_values');
			$formula = $this->_getReportFormula($calc_input_values, $propertydata, $loandata);
			$loan_amount = $loandata->getLoanAmount();
			$x = -1; $y = 0;
			
			while($x < 0) {
				$x = $loan_amount - $this->_num_div[$y];
				$y++;
			}
			
			$round = round($loan_amount / $this->_num_div[$y - 1]) * $this->_num_div[$y - 1];
					
			$rdata['loan_amount'] = number_format($loan_amount);
			$rdata['round_loan_amount'] = number_format($round);
			$rdata['loan_period'] = $loandata->getLoanTerm() / 12;
			$rdata['loan_period_remaining'] = $rdata['loan_period'] - (date("Y") - $loandata->getLoanDate()->format("Y"));
			$rdata['current_interest_rate'] = number_format($loandata->getInterestRate(), 1). "%";
			$rdata['current_interest_rate_2_years'] = number_format($loandata->getInterestRate() + 1, 1). "%";
			
			$rdata['property_price'] = number_format($propertydata->getPrice(), 2);
			$rdata['loan_amount_decimal'] = number_format($loan_amount, 2);
			
			$rdata['monthly_payment_reduce'] = number_format(round($formula['current_mortgage_scenario'][1]['monthly_payment'] - $formula['refi_scenario'][1]['monthly_payment'], 2), 2);
			
			$rdata['current_interest_payments_three_years'] = round($formula['current_mortgage_scenario'][3]['cumulative_interest'], -3);
			$rdata['refinance_interest_costs_three_years'] = round($formula['refi_scenario'][3]['cumulative_interest'], -3);
			$rdata['approx_savings_three_years'] = round($formula['current_mortgage_scenario'][3]['cumulative_interest'], -3) - round($formula['refi_scenario'][3]['cumulative_interest'], -3);
			
			$rdata['five_years_savings'] = number_format(round($formula['current_mortgage_scenario'][5]['cumulative_interest'], -3) - round($formula['refi_scenario'][5]['cumulative_interest'], -3));
			
			$rdata['total_loan_amount_current'] = number_format($formula['initial_loan_amount'], 2);
			$rdata['total_loan_amount_refi'] = number_format($formula['principal_remaining'], 2);
			$rdata['total_loan_amount_savings'] = number_format($formula['initial_loan_amount'] - $formula['principal_remaining'], 2);
			
			return $this->render('RefiBundle:Application:report.html.twig',
				array(
					'data' => $data,
					'prospect_property' => $id,
					'rdata' => $rdata,
				)
			);
		
		} else {
			return $this->render('RefiBundle:Application:report.html.twig',
				array(
					'data' => $data,
					'its_empty' => true,
				)
			);
		}
	}
	
	public function prospectreportAction(Request $request)
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

}
