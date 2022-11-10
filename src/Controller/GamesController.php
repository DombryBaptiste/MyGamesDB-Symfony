<?php

namespace App\Controller;

use App\Entity\Games;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GamesController extends AbstractController
{

    public function showGamesByPlatform(string $platform, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $repo = $em->getRepository(Games::class);
        $games = $repo->findAllGamesOrderByName($platform);
        return $this->render('games/index.html.twig', ['games' => $games, 'isConnected' => $session->get('isConnected'), 'userPseudo' => $session->get('userPseudo')]);
    }

    public function showGameByPlatformAndId(string $platform, string $id, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $repoTableGame = $em->getRepository(Games::class);
        $game = $repoTableGame->findOneBy(['id' => $id]);
        $userHaveGame = $repoTableGame->gameIsPosseded($session->get('UserID'),$id);

        return $this->render('games/individualGame.html.twig', ['haveGame' => $userHaveGame,'game' => $game, 'isConnected' => $session->get('isConnected'), 'userPseudo' => $session->get('userPseudo')]);
    }

    public function deleteGame(): Response{
        return $this->redirectToRoute('app_home');
    }
}
