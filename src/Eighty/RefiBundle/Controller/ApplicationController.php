<?php

namespace Eighty\RefiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ApplicationController extends Controller
{
    public function indexAction()
    {
        return $this->render('RefiBundle:Application:index.html.twig');
        //, array('name' => $name)
    }
    public function loginAction()
    {
        return $this->render('RefiBundle:Login:index.html.twig');
        //, array('name' => $name)
    }
}
