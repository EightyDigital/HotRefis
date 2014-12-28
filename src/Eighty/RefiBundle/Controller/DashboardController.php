<?php

namespace Eighty\RefiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
		$data = $this->get('application.defaultparams.handler')->getDefaultParams();
		
        return $this->render('RefiBundle:Dashboard:index.html.twig',
			array(
				'data' => $data,
			)
		);
    }
}
