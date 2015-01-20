<?php

namespace Eighty\RefiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends Controller
{
	private $_num_div = array(
		100000000, 10000000, 1000000, 
		100000, 10000, 1000, 100, 10, 1,
	);
	
    public function reportAction($id, Request $request)
    {
        $data = $this->get('application.defaultparams.handler')->getDefaultParams();
		$em = $this->getDoctrine()->getManager();
		
		$agency = $em->getRepository('RefiBundle:Clientcompany')->findOneById($data['company_id']);
		$data['agency_name'] = $agency->getName();
		$data['agency_subtitle'] = $agency->getSubtitle();
		$data['agency_html'] = $agency->getHtml();
		
		$session = new Session();
		$postdata = $request->request->all();
		$rdata = array();
		
		$prospect_properties = $session->get('prospect_properties');
		$calc_input_values = $session->get('calc_input_values');			
		
		if(isset($postdata['reportinput'])) { 
			return $this->render('RefiBundle:Report:sms.loading.html.twig',
				array()
			);
		}
		
		$loandata = $em->getRepository('RefiBundle:Prospectloan')->findOneByTransactionId($id);
		$propertydata = $em->getRepository('RefiBundle:Transactions')->findOneById($id);
		
		if(!empty($loandata) && !empty($propertydata) && $session->has('calc_input_values')) {
			$formula = $this->get('application.reportformula.handler')->getReportFormula($calc_input_values, $propertydata, $loandata);
			$loan_amount = $loandata->getLoanAmount();
			
			if($loan_amount <= 0) {
				$y = 0;
				$loan_amount = 0;
			} else {
				$x = -1; $y = 0;
				while($x < 0) {
					$x = $loan_amount - $this->_num_div[$y];
					$y++;
				}
				
			}
			
			$round = round($loan_amount / $this->_num_div[$y - 1]) * $this->_num_div[$y - 1];
			
			$rdata['property_name'] = $propertydata->getUrakey();
			$rdata['loan_amount'] = number_format(ceil($loan_amount / 50000) * 50000);
			$rdata['round_loan_amount'] = number_format($round);
			$rdata['loan_period'] = $loandata->getLoanTerm() / 12;
			$rdata['loan_period_remaining'] = $rdata['loan_period'] - (date("Y") - $loandata->getLoanDate()->format("Y"));
			$rdata['current_interest_rate'] = number_format($formula['current_third_year'], 1) . "%"; //number_format($loandata->getInterestRate(), 1). "%";
			$rdata['current_interest_rate_2_years'] = number_format($formula['current_fourth_year'], 1) . "%"; //number_format($loandata->getInterestRate() + 1, 1). "%";
			
			$rdata['property_price'] = number_format(ceil($propertydata->getPrice() / 50000) * 50000);
			
			$rdata['monthly_payment_reduce'] = number_format(round($formula['current_mortgage_scenario'][1]['monthly_payment'] - $formula['refi_scenario'][1]['monthly_payment']));  //, 2));
			
			$rdata['current_interest_payments_three_years'] = round($formula['current_mortgage_scenario'][3]['cumulative_interest'], -3);
			$rdata['refinance_interest_costs_three_years'] = round($formula['refi_scenario'][3]['cumulative_interest'], -3);
			$rdata['approx_savings_three_years'] = round($formula['current_mortgage_scenario'][3]['cumulative_interest'], -3) - round($formula['refi_scenario'][3]['cumulative_interest'], -3);
			
			$rdata['five_years_savings'] = number_format(round($formula['current_mortgage_scenario'][5]['cumulative_interest'], -3) - round($formula['refi_scenario'][5]['cumulative_interest'], -3));
			
			$rdata['total_loan_amount_current'] = number_format($formula['initial_loan_amount']); //, 2);
			$rdata['total_loan_amount_refi'] = number_format($formula['principal_remaining']); //, 2);
			$rdata['total_loan_amount_savings'] = number_format($formula['initial_loan_amount'] - $formula['principal_remaining']); //, 2);
			
			$rdata['monthly_payment_amount_current'] = number_format($formula['current_mortgage_scenario'][1]['monthly_payment']); //, 2);
			$rdata['monthly_payment_amount_refi'] = number_format($formula['refi_scenario'][1]['monthly_payment']); //, 2);
			$rdata['monthly_payment_amount_savings'] = number_format($formula['current_mortgage_scenario'][1]['monthly_payment'] - $formula['refi_scenario'][1]['monthly_payment']); //, 2);
			
			$rdata['interest_expenses_current'] = number_format($formula['current_mortgage_scenario'][1]['interest_cost_pa']); //, 2);
			$rdata['interest_expenses_refi'] = number_format($formula['refi_scenario'][1]['interest_cost_pa']); //, 2);
			$rdata['interest_expenses_savings'] = number_format($formula['current_mortgage_scenario'][1]['interest_cost_pa'] - $formula['refi_scenario'][1]['interest_cost_pa']); //, 2);
			
			$rdata['first_three_years_current'] = number_format($formula['current_mortgage_scenario'][3]['cumulative_interest']); //, 2);
			$rdata['first_three_years_refi'] = number_format($formula['refi_scenario'][3]['cumulative_interest']); //, 2);
			$rdata['first_three_years_savings'] = number_format($formula['current_mortgage_scenario'][3]['cumulative_interest'] - $formula['refi_scenario'][3]['cumulative_interest']); //, 2);
			
			$rdata['first_five_years_current'] = number_format($formula['current_mortgage_scenario'][5]['cumulative_interest']); //, 2);
			$rdata['first_five_years_refi'] = number_format($formula['refi_scenario'][5]['cumulative_interest']); //, 2);
			$rdata['first_five_years_savings'] = number_format($formula['current_mortgage_scenario'][5]['cumulative_interest'] - $formula['refi_scenario'][5]['cumulative_interest']); //, 2);
			
			$rdata['first_ten_years_current'] = number_format($formula['current_mortgage_scenario'][10]['cumulative_interest']); //, 2);
			$rdata['first_ten_years_refi'] = number_format($formula['refi_scenario'][10]['cumulative_interest']); //, 2);
			$rdata['first_ten_years_savings'] = number_format($formula['current_mortgage_scenario'][10]['cumulative_interest'] - $formula['refi_scenario'][10]['cumulative_interest']); //, 2);
			
			$rdata['total_expenses_current'] = number_format($formula['current_mortgage_scenario'][1]['principal_remaining']); //, 2);
			$rdata['total_expenses_refi'] = number_format($formula['refi_scenario'][1]['principal_remaining']); //, 2);
			$rdata['total_expenses_savings'] = number_format($formula['current_mortgage_scenario'][1]['principal_remaining'] - $formula['refi_scenario'][1]['principal_remaining']); //, 2);
			
			return $this->render('RefiBundle:Report:report.html.twig',
				array(
					'data' => $data,
					'rdata' => $rdata,
					'current_id' => $id,
					'ids' => $prospect_properties,
				)
			);
		
		} else {
			return $this->render('RefiBundle:Report:report.html.twig',
				array(
					'data' => $data,
					'its_empty' => true,
				)
			);
		}
	}
	
	public function prospectreportAction($hash, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		
		$reportlist = $em->getRepository('RefiBundle:Reportlist')->findOneByHash($hash);
		
		if(empty($reportlist)) {
			return $this->render('RefiBundle:Report:report.prospect.html.twig',
				array(
					'its_empty' => true,
				)
			);
		}
		
		$postdata = $request->request->all();
		if(isset($postdata['reportinput'])) { 
			return $this->render('RefiBundle:Report:prospect.confirm.html.twig',
				array(
					
				)
			);
		}
		
		if($reportlist->getStatus() == 2)
			$alert = true;
		else
			$alert = false;
		
		if(isset($postdata['reportName'])) {
			$reportlist->setStatus(2);
			$reportlist->setFullname($postdata['reportName']);
			$reportlist->setEmail($postdata['reportEmail']);
			$reportlist->setMobilenumber($postdata['reportMobile']);
			
			$em->flush();
			$alert = true;
		}
		
		if($reportlist->getStatus() < 1) {
			$reportlist->setStatus(1); $em->flush();
		}
		
		$broker = $em->getRepository('RefiBundle:Client')->findOneById($reportlist->getClientId());
		$agency = $em->getRepository('RefiBundle:Clientcompany')->findOneById($broker->getAgencyId());
		
		$data = array(
			'name' => $broker->getFullname(),
			'email' => $broker->getEmail(),
			'address' => $broker->getAddress(),
			'company' => $broker->getAgency(),
			'phone' => $broker->getPhone(),
			'title' => $broker->getTitle(),
			'age' => $broker->getAge(),
			'years_as_a_broker' => $broker->getYears(),
			'agency_name' => $agency->getName(),
			'agency_subtitle' => $agency->getSubtitle(),
			'agency_html' => $agency->getHtml(),
		);
		
		$calc_input_values = unserialize($reportlist->getCalculatorValues());
		
		$rdata = array();
		
		$loandata = $em->getRepository('RefiBundle:Prospectloan')->findOneByTransactionId($reportlist->getTransactionId());
		$propertydata = $em->getRepository('RefiBundle:Transactions')->findOneById($reportlist->getTransactionId());
		
		$formula = $this->get('application.reportformula.handler')->getReportFormula($calc_input_values, $propertydata, $loandata);
		$loan_amount = $loandata->getLoanAmount();
		$x = -1; $y = 0;
		
		while($x < 0) {
			$x = $loan_amount - $this->_num_div[$y];
			$y++;
		}
		
		$round = round($loan_amount / $this->_num_div[$y - 1]) * $this->_num_div[$y - 1];
		
		$rdata['property_name'] = $propertydata->getUrakey();
		$rdata['loan_amount'] = number_format(ceil($loan_amount / 50000) * 50000);
		$rdata['round_loan_amount'] = number_format($round);
		$rdata['loan_period'] = $loandata->getLoanTerm() / 12;
		$rdata['loan_period_remaining'] = $rdata['loan_period'] - (date("Y") - $loandata->getLoanDate()->format("Y"));
		$rdata['current_interest_rate'] = number_format($formula['current_third_year'], 1) . "%"; //number_format($loandata->getInterestRate(), 1). "%";
		$rdata['current_interest_rate_2_years'] = number_format($formula['current_fourth_year'], 1) . "%"; //number_format($loandata->getInterestRate() + 1, 1). "%";
		
		$rdata['property_price'] = number_format(ceil($propertydata->getPrice() / 50000) * 50000);
		
		$rdata['monthly_payment_reduce'] = number_format(round($formula['current_mortgage_scenario'][1]['monthly_payment'] - $formula['refi_scenario'][1]['monthly_payment']));  //, 2));
			
			$rdata['current_interest_payments_three_years'] = round($formula['current_mortgage_scenario'][3]['cumulative_interest'], -3);
			$rdata['refinance_interest_costs_three_years'] = round($formula['refi_scenario'][3]['cumulative_interest'], -3);
			$rdata['approx_savings_three_years'] = round($formula['current_mortgage_scenario'][3]['cumulative_interest'], -3) - round($formula['refi_scenario'][3]['cumulative_interest'], -3);
			
			$rdata['five_years_savings'] = number_format(round($formula['current_mortgage_scenario'][5]['cumulative_interest'], -3) - round($formula['refi_scenario'][5]['cumulative_interest'], -3));
			
			$rdata['total_loan_amount_current'] = number_format($formula['initial_loan_amount']); //, 2);
			$rdata['total_loan_amount_refi'] = number_format($formula['principal_remaining']); //, 2);
			$rdata['total_loan_amount_savings'] = number_format($formula['initial_loan_amount'] - $formula['principal_remaining']); //, 2);
			
			$rdata['monthly_payment_amount_current'] = number_format($formula['current_mortgage_scenario'][1]['monthly_payment']); //, 2);
			$rdata['monthly_payment_amount_refi'] = number_format($formula['refi_scenario'][1]['monthly_payment']); //, 2);
			$rdata['monthly_payment_amount_savings'] = number_format($formula['current_mortgage_scenario'][1]['monthly_payment'] - $formula['refi_scenario'][1]['monthly_payment']); //, 2);
			
			$rdata['interest_expenses_current'] = number_format($formula['current_mortgage_scenario'][1]['interest_cost_pa']); //, 2);
			$rdata['interest_expenses_refi'] = number_format($formula['refi_scenario'][1]['interest_cost_pa']); //, 2);
			$rdata['interest_expenses_savings'] = number_format($formula['current_mortgage_scenario'][1]['interest_cost_pa'] - $formula['refi_scenario'][1]['interest_cost_pa']); //, 2);
			
			$rdata['first_three_years_current'] = number_format($formula['current_mortgage_scenario'][3]['cumulative_interest']); //, 2);
			$rdata['first_three_years_refi'] = number_format($formula['refi_scenario'][3]['cumulative_interest']); //, 2);
			$rdata['first_three_years_savings'] = number_format($formula['current_mortgage_scenario'][3]['cumulative_interest'] - $formula['refi_scenario'][3]['cumulative_interest']); //, 2);
			
			$rdata['first_five_years_current'] = number_format($formula['current_mortgage_scenario'][5]['cumulative_interest']); //, 2);
			$rdata['first_five_years_refi'] = number_format($formula['refi_scenario'][5]['cumulative_interest']); //, 2);
			$rdata['first_five_years_savings'] = number_format($formula['current_mortgage_scenario'][5]['cumulative_interest'] - $formula['refi_scenario'][5]['cumulative_interest']); //, 2);
			
			$rdata['first_ten_years_current'] = number_format($formula['current_mortgage_scenario'][10]['cumulative_interest']); //, 2);
			$rdata['first_ten_years_refi'] = number_format($formula['refi_scenario'][10]['cumulative_interest']); //, 2);
			$rdata['first_ten_years_savings'] = number_format($formula['current_mortgage_scenario'][10]['cumulative_interest'] - $formula['refi_scenario'][10]['cumulative_interest']); //, 2);
			
			$rdata['total_expenses_current'] = number_format($formula['current_mortgage_scenario'][1]['principal_remaining']); //, 2);
			$rdata['total_expenses_refi'] = number_format($formula['refi_scenario'][1]['principal_remaining']); //, 2);
			$rdata['total_expenses_savings'] = number_format($formula['current_mortgage_scenario'][1]['principal_remaining'] - $formula['refi_scenario'][1]['principal_remaining']); //, 2);
		
		return $this->render('RefiBundle:Report:report.prospect.html.twig',
			array(
				'data' => $data,
				'rdata' => $rdata,
				'alert' => $alert,
			)
		);
    }
	
	public function blastsummaryAction()
	{
		$data = $this->get('application.defaultparams.handler')->getDefaultParams();
		$em = $this->getDoctrine()->getManager();
		
		$session = new Session();
		
		if(!$session->has('prospect_properties')) {
			return $this->redirect($this->generateUrl('refi_homepage'));
		}
		
		$prospect_properties = $session->get('prospect_properties');
		
		$pids = implode(",", $prospect_properties);
		$transactions_list = $em->getRepository('RefiBundle:Transactions')->fetchTransactionsIn($pids);
		
		$transactions = array();
		foreach($transactions_list as $i => $transaction) {
			if(isset($temp[$transaction["urakey"]]))
				$temp[$transaction["urakey"]] += 1;
			else
				$temp[$transaction["urakey"]] = 1;
			
			$transactions[$transaction["urakey"]]["name"] = $transaction["urakey"];
			$transactions[$transaction["urakey"]]["count"] = $temp[$transaction["urakey"]];
		}
		$session->clear();
		
		return $this->render('RefiBundle:Report:sms.confirm.html.twig',
			array(
				'data' => $data,
				'projects' => count($transactions),
				'properties' => count($prospect_properties),
				'transactions' => $transactions,
			)
		);
	}
}
