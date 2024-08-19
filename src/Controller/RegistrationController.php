<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
    #[Route('/register/new', name: 'app_register_new', options: ['expose' => true])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // dd($request);
        if ($request->get('password') != $request->get('passwordc')) {
            return new JsonResponse('Les mots de passe ne correspondent pas', 500);
        }
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => trim($request->get('username'))]);
        if ($user) {
            return new JsonResponse('Username déja exist', 500);
        }
        $user = new User();
        $user->setUsername(trim($request->get('username')));
        $user->setEmail($request->get('email'));
        $user->setPrenom($request->get('prenom'));
        $user->setNom($request->get('nom'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($userPasswordHasher->hashPassword(
            $user,
            $request->get('password')
        ));
        $user->setEnable(true);
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse("Veuillez contacter l'administrateur pour active votre compte!");
    }
}
