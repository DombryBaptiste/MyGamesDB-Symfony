<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    //#[Route('/home', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {   
        $session = $request->getSession();
        $isConnected = $session->get('isConnected');
        $repo = $em->getRepository(User::class);
        $users = $repo->findBy(['email' => 'aa@bb.cc']);

        if($isConnected){
            return $this->render('home/index.html.twig', [
            'isConnected' => true,
            ]);
        }else{
             return $this->render('home/index.html.twig', [
                    'isConnected' => false,
                    'users' => $users
                ]);
        }
    }
}
