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
use App\Entity\UserData;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class InscriptionController extends AbstractController {

    public function inscription(SessionInterface $session, EntityManagerInterface $em, Request $request): Response
    {

        if($session->get('isConnected')) {
             return $this->redirectToRoute('app_home');
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
                            $this->setSessionID($em, $data, $session);
                            //$this->createTablePerUser($em, $session);
                            return $this->redirectToRoute('app_home', ['isConnected' => $session->get('isConnected')]);
                        } else {
                            return $this->render('inscription/index.html.twig', ['isConnected' => $session->get('isConnected'), 'form' => $form->createView(), 'form_return' => 'L\'email est déja pris.']);
                        }                
                    } else {
                        return $this->render('inscription/index.html.twig', ['isConnected' => $session->get('isConnected'), 'form' => $form->createView(), 'form_return' => 'Les deux mots de passes ne correspondent pas.']);
                    }
                } else {
                    return $this->render('inscription/index.html.twig', ['isConnected' => $session->get('isConnected'), 'form' => $form->createView(), 'form_return' => 'Les deux emails ne correspondent pas.']);
                }
            } else {
                return $this->render('inscription/index.html.twig', ['isConnected' => $session->get('isConnected'), 'form' => $form->createView(), 'form_return' => 'formualire non soumis']);
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

    private function setSessionID(EntityManagerInterface $em, Array $data, SessionInterface $session) {

        $repo = $em->getRepository(User::class);
        $user = $repo->findOneBy(['email' => $data['email']]);
        $session->set('SessionID', $user->getId());
    }
    private function createTablePerUser(EntityManagerInterface $em, SessionInterface $session) {

        $metadata = $em->getClassMetadata(UserData::class);
        //dd($metadata);
        $metadata->setPrimaryTable(array('name' => $metadata->getTableName() . $session->get('SessionID')));
        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema(array($metadata));
    }
}
