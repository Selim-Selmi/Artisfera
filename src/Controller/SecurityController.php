<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface; 

class SecurityController extends AbstractController
{

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        // Si l'utilisateur est déjà connecté, on le redirige selon son rôle
        if ($this->getUser()) {
            return $this->redirectToRoute('app_redirect_by_role');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout()
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/redirect-by-role', name: 'app_redirect_by_role')]
    public function redirectByRole(): RedirectResponse
    {
        if ($this->getUser()) {
            $roles = $this->getUser()->getRoles();

            if (in_array('ROLE_ADMIN', $roles)) {
                return $this->redirectToRoute('app_admin');
            } elseif (in_array('ROLE_MEMBRE', $roles)) {
                return $this->redirectToRoute('app_membre');
            } elseif (in_array('ROLE_MUSIQUE', $roles)) {
                return $this->redirectToRoute('app_Musique_list');
            } elseif (in_array('ROLE_PEINTURE', $roles)) {
                return $this->redirectToRoute('app_peinture_list');
            } elseif (in_array('ROLE_TEXTILE', $roles)) {
                return $this->redirectToRoute('app_textile');
            } elseif (in_array('ROLE_CERAMIQUE', $roles)) {
                return $this->redirectToRoute('app_ceramique');
            }

        }
        return $this->redirectToRoute('app_login');


        // Si aucun rôle trouvé, on redirige vers la page d'accueil
    }
}
