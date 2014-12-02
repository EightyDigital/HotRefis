<?php

namespace Eighty\RefiBundle\Controller;

use Eighty\RefiBundle\Entity\Sectorlist;
use Eighty\RefiBundle\Entity\Creditused;
use Eighty\RefiBundle\Entity\Postalregion;

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
		$sectors_owned = count($em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($id));

        return $this->render('RefiBundle:Application:index.html.twig',
			array(
				'name' => $usr->getFullname(),
				'credits' => $credits,
				'sectors_owned' => $sectors_owned,
			)
		);
    }

    public function listAction()
    {
		$em = $this->getDoctrine()->getManager();
		$usr = $this->get('security.context')->getToken()->getUser();
		$id = $usr->getId();
		$credits = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($id);
		$sectors_owned = count($em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($id));
		$paginator = $this->get('knp_paginator');
		
		$prospectlist = $em->getRepository('RefiBundle:Prospectlist')->getProspectListContactedEngaged($id);
		
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
				'name' => $usr->getFullname(),
				'credits' => $credits,
				'sectors_owned' => $sectors_owned,
				'prospect_list' => $prospect_list,
			)
		);
    }

	public function prospectAction()
    {
		$em = $this->getDoctrine()->getManager();
		$usr = $this->get('security.context')->getToken()->getUser();
		$id = $usr->getId();
		$credits = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($id);
		$sectors_owned = count($em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($id));

        return $this->render('RefiBundle:Application:prospect.html.twig',
			array(
				'name' => $usr->getFullname(),
				'credits' => $credits,
				'sectors_owned' => $sectors_owned,
			)
		);
    }

	public function campaignAction()
    {
		$em = $this->getDoctrine()->getManager();
		$usr = $this->get('security.context')->getToken()->getUser();
		$id = $usr->getId();
		$credits = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($id);
		$sectors_owned = count($em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($id));

        return $this->render('RefiBundle:Application:campaign.html.twig',
			array(
				'name' => $usr->getFullname(),
				'credits' => $credits,
				'sectors_owned' => $sectors_owned,
			)
		);
    }

    public function calculatorAction()
    {
		$em = $this->getDoctrine()->getManager();
		$usr = $this->get('security.context')->getToken()->getUser();
		$id = $usr->getId();
		$credits = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($id);
		$sectors_owned = count($em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($id));
		
        return $this->render('RefiBundle:Application:calculator.html.twig',
			array(
				'name' => $usr->getFullname(),
				'credits' => $credits,
				'sectors_owned' => $sectors_owned,
			)
		);
    }

    public function reportAction()
    {
        $em = $this->getDoctrine()->getManager();
		$usr = $this->get('security.context')->getToken()->getUser();
		$id = $usr->getId();
		$credits = $em->getRepository('RefiBundle:Client')->getRemainingCreditsById($id);
		$sectors_owned = count($em->getRepository('RefiBundle:Transactions')->fetchSectorsInListByClientId($id));
		
        return $this->render('RefiBundle:Application:report.html.twig',
			array(
				'name' => $usr->getFullname(),
				'credits' => $credits,
				'sectors_owned' => $sectors_owned,
			)
		);
    }

	/*-------------------------------------------------/
	|	route: <domain>/api/filter/property
	|	postdata: none;
	--------------------------------------------------*/
	public function filterPropertyAction()
	{
		$em = $this->getDoctrine()->getManager();
		/*
		$sectors = array('01','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60','61','63','64','65','66','67','68','73','75','76','77','78','79','80','82');
		
		$sector_data = array();
		foreach($sectors as $sector) {
			$property_data = $em->getRepository('RefiBundle:Transactions')->filterSectorByCode($sector);
			$sector_info = $em->getRepository('RefiBundle:Transactions')->fetchSectorInfoByCode($sector);
			if(!empty($sector_info)) {
				$prospect_count = count($property_data);
				if($prospect_count > 0) {
					$sector_data[$sector]['name'] = $sector_info[0]['name'];
					$sector_data[$sector]['sector_code'] = $sector;
					$sector_data[$sector]['longitude'] = $sector_info[0]['longitude'];
					$sector_data[$sector]['latitude'] = $sector_info[0]['latitude'];
					$sector_data[$sector]['total_sector_prospects'] = $prospect_count; 
				}
			}
		}
		*/
		
		$property_data = $em->getRepository('RefiBundle:Transactions')->filterSectorByCode();
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
				if($debt >= $postdata['debt_min'] && $debt <= $postdata['debt_max']) {
					$score++; $test = $test . "h"; }
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
				$sector[$val['sector']]['properties'][$val['urakey']]['prospects'][$key]['prospect_id'] = $val['prospectId'];
				/*$sector[$val['sector']]['properties'][$val['urakey']]['prospects'][$key]['prospect_score'] = $temp_score;*/
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
        $response->headers->set('Content-Type', 'application/json');

        return $response;
	}

}
