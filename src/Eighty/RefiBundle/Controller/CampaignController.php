<?php

namespace Eighty\RefiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CampaignController extends Controller
{
    public function campaignAction()
    {
		$data = $this->get('application.defaultparams.handler')->getDefaultParams();

        return $this->render('RefiBundle:Campaign:campaign.html.twig',
			array(
				'data' => $data,
			)
		);
    }
}
