<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, PasswordType};

class InscriptionController extends AbstractController
{
    //#[Route('/inscription', name: 'app_inscription')]
    public function inscription(Request $request, EntityManagerInterface $em): Response
    {
        $session = $request->getSession();
        $session->set('session_error', "ERREUR FATALE");
        $isConnected = $session->get('isConnected');
        if($isConnected) {
             return $this->render('inscription/index.html.twig', ['isConnected' => $isConnected]);
        } else {
            $form = $this->createFormBuilder()
                ->add('pseudo', TextType::class,
                    ['label' => 'Pseudo :',
                    'row_attr' => ['class' => 'rowForm']])
                ->add('email', EmailType::class, 
                    ['label' => 'Email :',
                    'row_attr' => ['class' => 'rowForm']])
                ->add('email2', EmailType::class, 
                    ['label' => 'Confirmer email :',
                    'row_attr' => ['class' => 'rowForm']])
                ->add('password', PasswordType::class, ['label' => 'Mot de passe :',
                    'row_attr' => ['class' => 'rowForm']])
                ->add('password2', PasswordType::class, ['label' => 'Confirmer Mot de passe :',
                    'row_attr' => ['class' => 'rowForm']])
                ->getForm()
            ;

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $data = $form->getData();
                //dd($data);
                $user = new User;
                $user->setPseudo($data['pseudo']);
                $user->setEmail($data['email']);
                $user->setPassword(sha1($data['password']));
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('app_home');
            } else {
                return $this->render(
                    'inscription/index.html.twig',
                   ['isConnected' => $isConnected,
                    'form' => $form->createView(),
                    'session_error' => 'ERREUR FATALE'
                   ]);
            }
        }
    }
}
