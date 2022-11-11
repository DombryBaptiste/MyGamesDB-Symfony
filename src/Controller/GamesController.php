<?php

namespace App\Controller;

use App\Entity\Games;
use App\Entity\UserData;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class GamesController extends AbstractController
{

    public function showGamesByPlatform(string $platform, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $repo = $em->getRepository(Games::class);
        $games = $repo->findAllGamesOrderByName($platform);
        return $this->render('games/index.html.twig', ['games' => $games, 'isConnected' => $session->get('isConnected'), 'userPseudo' => $session->get('userPseudo')]);
    }

    /**
     * @throws Exception
     */
    public function showGameByPlatformAndId(string $platform, string $id, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $repoTableGame = $em->getRepository(Games::class);
        $repoUserData = $em->getRepository(UserData::class);
        $game = $repoTableGame->findOneBy(['id' => $id]);
        $userHaveGame = $repoUserData->gameIsPossessed($session->get('UserID'),$id);

        return $this->render('games/individualGame.html.twig', ['haveGame' => $userHaveGame,'game' => $game, 'isConnected' => $session->get('isConnected'), 'userPseudo' => $session->get('userPseudo')]);
    }

    public function deleteGame(string $platform, string $id, EntityManagerInterface $em, SessionInterface $session){
        $repoTableData = $em->getRepository(UserData::class);
        $row = $repoTableData ->findOneBy(['id_game' => $id, 'id_user' => $session->get('UserID')]);
        $em->remove($row);
        $em->flush();

        return $this->redirectToRoute('app_one_game', ['id' => $id, 'platform' => $platform]);
    }

    /**
     * @throws Exception
     */
    public function addGame(string $platform, string $id, EntityManagerInterface $em, SessionInterface $session){
        $row = $this->initRow($id, $session->get('UserID'));
        $em->persist($row);
        $em->flush();

        return $this->redirectToRoute('app_one_game', ['id' => $id, 'platform' => $platform]);
    }


    // TOOLS

    private function initRow($id_game, $id_user): UserData
    {
        $res = new UserData();
        $res->setIdGame($id_game);
        $res->setIdUser($id_user);
        $res->setAdded(new \DateTime());
        return $res;

    }



}
