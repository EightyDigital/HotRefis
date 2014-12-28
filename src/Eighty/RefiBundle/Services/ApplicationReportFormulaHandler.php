<?php

namespace Eighty\RefiBundle\Services;

use Doctrine\ORM\EntityManager;

class ApplicationReportFormulaHandler
{
	protected $em;
	
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function getReportFormula($calc_input_values, $propertydata, $loandata)
	{
		$formula = array();
		
		if($loandata->getLoanTerm() < 1 || is_null($loandata->getLoanTerm())) 
			$calc_input_values['loan_term'] = 360 / 12;
		else 
			$calc_input_values['loan_term'] = $loandata->getLoanTerm() / 12;
		
		$property_data['price'] = $propertydata->getPrice();
		$property_data['newprice'] = $propertydata->getNewPrice();
		if(is_null($property_data['newprice'])) $property_data['newprice'] = $property_data['price'] * 2;
		
		$months = $calc_input_values['loan_term'] * 12;
		
		$formula['initial_loan_amount']			= $calc_input_values['ltv_at_purchase'] * $property_data['price'];
		$formula['equity_at_mortgage_rate'] 	= $property_data['price'] - $formula['initial_loan_amount'];
		
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
		$formula['equity'] 						= $property_data['newprice'] - $formula['principal_remaining'];
		$formula['ltv_new'] 					= round(($formula['principal_remaining'] / $property_data['newprice']) * 100);
		
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

}