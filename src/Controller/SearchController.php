<?php

namespace App\Controller;

use App\Entity\Games;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    public function index(string $string, Request $request, EntityManagerInterface $em, Session $session): Response
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
        if(strlen($string) <= 2){
            $this->addFlash('error', 'Veuillez entrer au moins 3 caractères');
            return $this->redirectToRoute('app_home');
        }
        $keywords = explode(' ', $string);
        $like = "";
        foreach($keywords as $keyword) {
            if(strlen($keyword) >= 3) {
                $like.= " UPPER(name) LIKE '%".strtoupper($keyword)."%' OR";
            }
        }
        $like = substr($like, 0, strlen($like) - 3);
        $repo = $em->getRepository(Games::class);
        $games = $repo->findSearch($like);
        return $this->render('search/index.html.twig', ['session' => $session, 'string' => $string, 'games' => $games, 'formBar' => $formBar->createView()]);
    }
}
