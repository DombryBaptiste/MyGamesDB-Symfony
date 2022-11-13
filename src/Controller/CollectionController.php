<?php

namespace App\Controller;

use App\Entity\Games;
use App\Entity\UserData;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CollectionController extends AbstractController
{
    public function index(Request $request, SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        $formBar = $this->createFormBuilder()
            ->add('search', TextType::class,
                ['row_attr' => ['class' => 'search_bar']])
            ->getForm();
        $formBar->handleRequest($request);
        if($formBar->isSubmitted() && $formBar->isValid()) {
            $data = $formBar->getData();
            return $this->redirectToRoute('app_search', ['string' => $data['search']]);
        }
        $platform = $doctrine->getManager()->getRepository(Games::class)->getPlatforms();
        $games = $doctrine->getManager()->getRepository(UserData::class)->getGamesByIDUser($session->get('UserID'));
        return $this->render('collection/index.html.twig', ['formBar' => $formBar->createView(), 'session' => $session, 'platform' => $platform, 'games' => $games]);
    }
}
