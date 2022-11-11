<?php

namespace App\Controller;

use App\Entity\Games;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\UserData;
use Doctrine\ORM\Tools\SchemaTool;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController {

    public function index(SessionInterface $session, EntityManagerInterface $em, Request $request): Response
    {
        $formBar = $this->createFormBuilder()
            ->add('search', TextType::class,
            ['row_attr' => ['class' => 'search_bar']])
            ->getForm();
        $formBar->handleRequest($request);

        $repo = $em->getRepository(Games::class);
        $last_games = $repo->findLastGameAdded($session->get('UserID'));
        if($formBar->isSubmitted() && $formBar->isValid()) {
            $data = $formBar->getData();
            return $this->redirectToRoute('app_search', ['string' => $data['search']]);
        }
        return $this->render('home/home.html.twig', ['error' => $session->get('error'),'formBar' => $formBar->createView(), 'isConnected' => $session->get('isConnected'), 'userPseudo' => $session->get('userPseudo'), 'lastGames' => $last_games]);
        
    }
}
