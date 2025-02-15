<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SyrineController extends AbstractController
{
    #[Route('/syrine', name: 'app_syrine')]
    public function index(): Response
    {
        return $this->render('syrine/index.html.twig', [
            'controller_name' => 'SyrineController',
        ]);
    }
}
