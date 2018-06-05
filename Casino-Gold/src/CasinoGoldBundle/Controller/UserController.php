<?php

namespace CasinoGoldBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }
    /**
     * @Route("/show")
     */
    public function showAction()
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, '...');
        return $this->render('user/show.html.twig');
    }
}
