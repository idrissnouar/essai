<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(Request $request, Security $security): Response
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $redirectResponse = $security->login($form->getData(), 'app_user_provider');

                return $redirectResponse;
            } else {
                $errors = [];
                foreach ($form as $fieldName => $formField) {
                    // each field has an array of errors
                    $errors[$fieldName] = $formField->getErrors()->__toString();
                }
                var_dump($errors);
//                die();
            }
        }

        return $this->render('public/login.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPassword()));
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('login');
            } else {
                $errors = [];
                foreach ($form as $fieldName => $formField) {
                    // each field has an array of errors
                    $errors[$fieldName] = $formField->getErrors()->__toString();
                }
                var_dump($errors);
//                die();
            }
        }

        return $this->render('public/register.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/forgot', name: 'forgot', methods: ['GET', 'POST'])]
    public function forgot(): Response
    {

        return $this->render('public/forgot.html.twig');
    }

    #[Route('/recover/{token}', name: 'recover', methods: ['GET', 'POST'])]
    public function recover($token): Response
    {

        return $this->render('public/recover.html.twig');
    }
}
