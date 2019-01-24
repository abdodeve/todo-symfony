<?php

namespace TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/test")
     */
    public function indexAction()
    {
        return $this->render('TestBundle:Default:index.html.twig');
    }

    /**
     * @Route("/test/fetch", name="testTodos")
     */
    public function fetchAction(Request $request)
    {
        return $this->json(array('username' => 'jane.doe'));
    }

}
