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
                ->add('emailConfirm', EmailType::class, 
                    ['label' => 'Confirmer email :',
                    'row_attr' => ['class' => 'rowForm']])
                ->add('password', PasswordType::class, ['label' => 'Mot de passe :',
                    'row_attr' => ['class' => 'rowForm']])
                ->add('passwordConfirm', PasswordType::class, ['label' => 'Confirmer Mot de passe :',
                    'row_attr' => ['class' => 'rowForm']])
                ->getForm()
            ;

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $data = $form->getData();

                $newUserPseudo = $data['pseudo'];
                $newUserEmail = $data['email'];
                $newUserEmailConfirm = $data['emailConfirm'];
                $newUserPassword = $data['password'];
                $newUserPasswordConfirm = $data['passwordConfirm'];

                if($newUserEmail == $newUserEmailConfirm){
                    if($newUserPassword == $newUserPasswordConfirm){
                        if(!$this->isUsedEmail($em, $data)) {
                            $user = new User;
                            $this->initializeUser($user, $data);
                            $em->persist($user);
                            $em->flush();
                            $session->set('Pseudo', $newUserPseudo);
                            return $this->render('home/index.html.twig', ['isConnected' => $isConnected]);
                        } else {
                            return $this->render('inscription/index.html.twig', ['isConnected' => $isConnected, 'form' => $form->createView(), 'form_return' => 'L\'email est déja pris.']);
                        }                
                    } else {
                        return $this->render('inscription/index.html.twig', ['isConnected' => $isConnected, 'form' => $form->createView(), 'form_return' => 'Les deux mots de passes ne correspondent pas.']);
                    }
                } else {
                    return $this->render('inscription/index.html.twig', ['isConnected' => $isConnected, 'form' => $form->createView(), 'form_return' => 'Les deux emails ne correspondent pas.']);
                }
            } else {
                return $this->render('inscription/index.html.twig', ['isConnected' => $isConnected, 'form' => $form->createView(), 'form_return' => 'formualire non soumis']);
            }
        }
    }

    // OUTILS

    private function initializeUser(User $user, Array $data){
        $user->setPseudo($data['pseudo']);
        $user->setEmail($data['email']);
        $user->setPassword(sha1($data['password']));
    }

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
