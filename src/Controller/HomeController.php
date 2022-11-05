<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    //#[Route('/home', name: 'app_home')]
    public function index(Request $request): Response
    {   
        $session = $request->getSession();
        $isConnected = $session->get('isConnected');
        if($isConnected){
            return $this->render('home/index.html.twig', [
            'isConnected' => true,
            ]);
        }else{
             return $this->render('home/index.html.twig', [
                    'isConnected' => false,
                ]);
        }
    }
}
