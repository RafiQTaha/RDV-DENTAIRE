<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class IndexController extends AbstractController
{
    public function __construct(private Security $security)
    {
        $this->security = $security;
    }
    #[Route('/', name: 'app_index')]
    public function index()
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_index');
        }
        if ($this->security->isGranted('ROLE_ETUDIANT')) {
            return $this->redirectToRoute('app_etudiant_rdv_listing');
        }

        // return $this->redirectToRoute('app_admin_user');
    }
}
