<?php

namespace App\Controller;

use App\Entity\Games;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{

    public function showGamesByPlatform(string $platform, EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(Games::class);
        $games = $repo->findBy(['platform' => $platform]);
        return $this->render('games/index.html.twig', ['games' => $games]);
    }

    public function showGameByPlatformAndId(string $platform, string $id): Response
    {
        return new Response("<h1>".$platform." + ".$id."</h1>");
    }
}
