<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\UserData;
use Doctrine\ORM\Tools\SchemaTool;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController {

    public function index(SessionInterface $session, EntityManagerInterface $em): Response
    {     
        return $this->render('home/home.html.twig', ['isConnected' => $session->get('isConnected'), 'userPseudo' => $session->get('userPseudo')]);
        
    }
}
