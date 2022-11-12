<?php

namespace App\Controller;


use App\Entity\Games;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProfilController extends AbstractController
{
    public function index(SessionInterface $session, EntityManagerInterface $em, Request $request): Response
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
        return $this->render('profil/index.html.twig', ['formBar' => $formBar->createView(), 'session' => $session]);
    }

    public function changePseudo(SessionInterface $session, EntityManagerInterface $em, Request $request, ManagerRegistry $doctrine): Response {
        $formBar = $this->createFormBuilder()
            ->add('search', TextType::class,
                ['row_attr' => ['class' => 'search_bar']])
            ->getForm();
        $formBar->handleRequest($request);
        if($formBar->isSubmitted() && $formBar->isValid()) {
            $data = $formBar->getData();
            return $this->redirectToRoute('app_search', ['string' => $data['search']]);
        }

        $formPseudo = $this->createFormBuilder()
            ->add('pseudo', TextType::class,
            ['row_attr' => ['class' => 'new_pseudo_input']])
            ->getForm();
        $formPseudo->handleRequest($request);
        if($formPseudo->isSubmitted() && $formPseudo->isValid()) {
            $data = $formPseudo->getData();
            $doctrine->getManager()->getRepository(User::class)->changePseudo($data['pseudo'], $session->get('UserID'));
            $session->set('userPseudo', $data['pseudo']);
            $this->addFlash('error', 'Pseudo changé');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('profil/pseudo.html.twig', ['formPseudo' => $formPseudo->createView(), 'formBar' => $formBar->createView(), 'session' => $session]);
    }

    public function changeEmail(SessionInterface $session, EntityManagerInterface $em, Request $request, ManagerRegistry $doctrine): Response {
        $formBar = $this->createFormBuilder()
            ->add('search', TextType::class,
                ['row_attr' => ['class' => 'search_bar']])
            ->getForm();
        $formBar->handleRequest($request);
        if($formBar->isSubmitted() && $formBar->isValid()) {
            $data = $formBar->getData();
            return $this->redirectToRoute('app_search', ['string' => $data['search']]);
        }

        $formEmail = $this->createFormBuilder()
            ->add('email', TextType::class,
                ['row_attr' => ['class' => 'new_email_input']])
            ->getForm();
        $formEmail->handleRequest($request);
        if($formEmail->isSubmitted() && $formEmail->isValid()) {
            $data = $formEmail->getData();
            if(!$this->isUsedEmail($em,$data)){
                $doctrine->getManager()->getRepository(User::class)->changeEmail($data['email'], $session->get('UserID'));
                $session->set('userEmail', $data['email']);
                $this->addFlash('error', 'Email changé');
                return $this->redirectToRoute('app_home');
            }
            $this->addFlash('error', 'Email déja utilisé');
            return $this->render('profil/email.html.twig', ['formEmail' => $formEmail->createView(), 'formBar' => $formBar->createView(), 'session' => $session]);
        }
        return $this->render('profil/email.html.twig', ['formEmail' => $formEmail->createView(), 'formBar' => $formBar->createView(), 'session' => $session]);
    }

    // TOOLS

    private function isUsedEmail(EntityManagerInterface $em, Array $data): bool{
        $repo = $em->getRepository(User::class);
        $user = $repo->findOneBy(['email' => $data['email']]);
        if(isset($user)){
            return true;
        } else {
            return false;
        }
    }
}
