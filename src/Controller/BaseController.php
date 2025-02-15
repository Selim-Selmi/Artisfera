<?php

namespace App\Controller;

use App\Repository\PeintureRepository;
use App\Repository\MusiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    #[Route('/base', name: 'app_base', methods: ['GET'])]
    public function base(PeintureRepository $peintureRepository,MusiqueRepository $musiqueRepository): Response
    {
        $peinturesDB = $peintureRepository->findAll();
        $musiquesDB = $musiqueRepository->findAll();
        return $this->render('base.html.twig',[
        'peintures' => $peinturesDB,
        'Musiques' => $musiquesDB,]);
    }
}
