<?php

namespace Eighty\RefiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListController extends Controller
{
    public function listAction()
    {
		$data = $this->get('application.defaultparams.handler')->getDefaultParams();
		$em = $this->getDoctrine()->getManager();
		
		$paginator = $this->get('knp_paginator');
		$prospectlist = $em->getRepository('RefiBundle:Reportlist')->getReportListContactedEngaged($data['id']);
		
		$temp_prospect_list = array();
		foreach($prospectlist as $key => $val) {
			if(isset($temp[$val['regionCode']][$val['prospectId']]))
				$temp[$val['regionCode']][$val['prospectId']] += 1;
			else
				$temp[$val['regionCode']][$val['prospectId']] = 1;
			
			$temp_prospect_list[$val['regionCode']]['name'] = $val['sector_name'];
			$temp_prospect_list[$val['regionCode']]['prospects'][$val['prospectId']] = array(
											'prospectId' => $val['prospectId'],
											'property_owned' => $temp[$val['regionCode']][$val['prospectId']],
											'status' => $val['status'],
											'note' => $val['note'],
											'fullname' => $val['fullname'],
											'email' => $val['email'],
											'mobilenumber' => $val['mobilenumber'],
											'id' => $val['id'],
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
		
		return $this->render('RefiBundle:List:list.html.twig',
			array(
				'data' => $data,
				'prospect_list' => $prospect_list,
			)
		);
    }
}
