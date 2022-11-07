<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, PasswordType};

class ConnexionController extends AbstractController
{
    //#[Route('/connexion', name: 'app_connexion')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $session = $request->getSession();
        $isConnected = $session->get('isConnected');
        $form = $this->createFormBuilder()
                ->add('email', EmailType::class, 
                    ['label' => 'Email :',
                    'row_attr' => ['class' => 'rowForm']])
                ->add('password', PasswordType::class, ['label' => 'Mot de passe :',
                    'row_attr' => ['class' => 'rowForm']])
                ->getForm()
            ;

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $data = $form->getData();

                $userEmailConnexion = $data['email'];
                $userPasswordConnexion = $data['password'];

                if($this->isUsedEmail($em, $data)){
                    if($this->isGoodPassword($em, $data)){
                        $session->set('isConnected', true);
                        $pseudo = getPseudoWithEmail($em, $data);
                        $session->set('userPseudo', $pseudo);
                        return $this->render('home/index.html.twig', ['isConnected' => $isConnected, 'userPseudo' => $session->get('userPseudo')]);
                    } else {
                    return $this->render('connexion/index.html.twig', ['isConnected' => $isConnected, 'form' => $form->createView(), 'form_return' => 'Mot de passe invalide.']);
                    }
                } else {
                     return $this->render('connexion/index.html.twig', ['isConnected' => $isConnected, 'form' => $form->createView(), 'form_return' => 'Aucun compte est associé a cet email.']);
                }

            } else {
                return $this->render('connexion/index.html.twig', ['isConnected' => $isConnected, 'form' => $form->createView()]);
            }
        
        
    }

    public function disconect(Request $request): Response 
    {
        $session = $request->getSession();
        $isConnected = $session->get('isConnected');
        if($isConnected) {
            $session->set('isConnected', false);
             return $this->redirectToRoute('app_home', ['isConnected' => $isConnected]);
        } else {
            return $this->redirectToRoute('app_home', ['isConnected' => $isConnected]);
        }
    }

    // OUTILS

    private function isUsedEmail(EntityManagerInterface $em, Array $data): bool{
        $repo = $em->getRepository(User::class);
        $user = $repo->findOneBy(['email' => $data['email']]);
        if(isset($user)){
            return true;
        } else {
            return false;
        } 
    }

    private function isGoodPassword(EntityManagerInterface $em, Array $data): bool {
        $repo = $em->getRepository(User::class);
        $user = $repo->findOneBy(['email' => $data['email']]);
        if(sha1($data['password']) == $user->getPassword()){
            return true;
        } else {
            return false;
        }
    }

    private function getPseudoWithEmail(EntityManagerInterface $em, array $data: string {
        $repo = $em->getRepository(User::class);
        $user = $repo->findOneBy(['email' => $data['email']]);
        return $user->getPseudo();
    }
}
