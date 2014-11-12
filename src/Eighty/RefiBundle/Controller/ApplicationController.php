<?php

namespace Eighty\RefiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    public function indexAction()
    {
        return $this->render('RefiBundle:Application:index.html.twig');
        //, array('name' => $name)
    }

    public function addAction()
    {
        return $this->render('RefiBundle:Application:add.html.twig');
        //, array('name' => $name)
    }
    public function listAction()
    {
        return $this->render('RefiBundle:Application:list.html.twig');
        //, array('name' => $name)
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

    public function filterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postdata = $request->request->all();
        $main_data = array();

        if (!isset($postdata['income_min'])) $postdata['income_min'] = 0;
        if (!isset($postdata['income_max'])) $postdata['income_max'] = 10000000;
        if (!isset($postdata['dcode_min'])) $postdata['dcode_min'] = 0;
        if (!isset($postdata['dcode_max'])) $postdata['dcode_max'] = 40;
        if (!isset($postdata['age_min'])) $postdata['age_min'] = 0;
        if (!isset($postdata['age_max'])) $postdata['age_max'] = 99;
        if (!isset($postdata['limit'])) $postdata['limit'] = 1000;
        if (!isset($postdata['offset'])) $postdata['offset'] = 0;

        $prospect_data = $em->getRepository('RefiBundle:Prospect')->filterProspects($postdata);

        $n = 0;
        foreach($prospect_data as $row) {
            $main_data[$n] = $row;
            $main_data[$n]['properties'] = $em->getRepository('RefiBundle:Prospect')->fetchTransactionsByProspectId($row['id']);
            $n++;
        }

        $response = new Response(json_encode($main_data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function shortlistAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postdata = $request->request->all();
    }
}
