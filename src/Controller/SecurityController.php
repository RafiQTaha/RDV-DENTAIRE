<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    private UserPasswordHasherInterface $passwordEncoder;
    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route(path: '/login', name: 'app_login', options: ['expose' => true])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout', options: ['expose' => true])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/forgot', name: 'forgot_password', options: ['expose' => true])]
    public function forgot(): void
    {
        dd("forgot password ?");
    }

    #[Route('/passwordchange', name: 'app_reset_password', options: ['expose' => true])]
    public function app_reset_password(Request $request, ManagerRegistry $doctrine)
    {
        // dd($request);
        $em = $doctrine->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());

        if (!$this->passwordEncoder->isPasswordValid($user, $request->get("old_password"))) {
            return new JsonResponse("Votre mot de passe est incorrect !", 500);
        }
        $user->setPassword($this->passwordEncoder->hashPassword(
            $user,
            $request->get('nv_password')
        ));

        $em->flush();
        return new JsonResponse("Bien Enregistre!", 200);
    }
}
