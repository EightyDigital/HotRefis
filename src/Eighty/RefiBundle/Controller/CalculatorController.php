<?php

namespace Eighty\RefiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class CalculatorController extends Controller
{
    public function calculatorAction(Request $request)
    {
		$data = $this->get('application.defaultparams.handler')->getDefaultParams();
		$em = $this->getDoctrine()->getManager();
		
		$session = new Session();
		
		if($session->has('prospect_ids')) {
			$postdata = $request->request->all();
			if (!isset($postdata['ltv_at_purchase'])) $postdata['ltv_at_purchase'] = 80;
			if (!isset($postdata['existing_loan_mortgage_rate'])) $postdata['existing_loan_mortgage_rate'] = 3;
			
			if($postdata['ltv_at_purchase'] !== 0 && $postdata['existing_loan_mortgage_rate'] !== 0 && isset($postdata['calc_form'])) {
				$postdata['ltv_at_purchase'] = $postdata['ltv_at_purchase'] / 100;
				$postdata['existing_loan_mortgage_rate'] = $postdata['existing_loan_mortgage_rate'] / 100;
				
				if ($postdata['current_first_year'] == "") $postdata['current_first_year'] = '3';
				if ($postdata['current_second_year'] == "") $postdata['current_second_year'] = '3';
				if ($postdata['current_third_year'] == "") $postdata['current_third_year'] = '4';
				if ($postdata['current_fourth_year'] == "") $postdata['current_fourth_year'] = '4';
				if ($postdata['current_fifth_year'] == "") $postdata['current_fifth_year'] = '5';
				if ($postdata['current_onwards'] == "") $postdata['current_onwards'] = '5';
				
				if ($postdata['refi_first_year'] == "") $postdata['refi_first_year'] = '2';
				if ($postdata['refi_second_year'] == "") $postdata['refi_second_year'] = '2';
				if ($postdata['refi_third_year'] == "") $postdata['refi_third_year'] = '3';
				if ($postdata['refi_fourth_year'] == "") $postdata['refi_fourth_year'] = '3';
				if ($postdata['refi_fifth_year'] == "") $postdata['refi_fifth_year'] = '4';
				if ($postdata['refi_onwards'] == "") $postdata['refi_onwards'] = '4';
				
				$session->set('calc_input_values', $postdata);
				
				$prospect_ids = $session->get('prospect_ids');
				$sector_codes = $session->get('sector_codes');				
				$temp = $em->getRepository('RefiBundle:Transactions')->fetchLoansByProspectIds(implode(',', $prospect_ids), implode(',', $sector_codes));
				
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
			
			return $this->render('RefiBundle:Calculator:calculator.html.twig',
				array(
					'data' => $data,
				)
			);
		} else {
			return $this->render('RefiBundle:Calculator:calculator.html.twig',
				array(
					'data' => $data,
					'its_empty' => true,
				)
			);
		}
	}
}
