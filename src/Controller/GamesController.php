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
        return $this->render('games/index.html.twig', ['games' => $games, 'isConnected' => $session->get('isConnected')]);
    }

    public function showGameByPlatformAndId(string $platform, string $id): Response
    {
        return new Response("<h1>".$platform." + ".$id."</h1>");
    }
}
