<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, PasswordType};

class InscriptionController extends AbstractController
{
    //#[Route('/inscription', name: 'app_inscription')]
    public function inscription(Request $request): Response
    {
        $session = $request->getSession();
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
            //$form->handleRequest($request);
            return $this->render(
                'inscription/index.html.twig',
                ['isConnected' => $isConnected,
                 'form' => $form->createView()
                ]);
        }
    }
}
