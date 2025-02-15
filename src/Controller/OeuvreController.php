<?php
// OeuvreController.php

namespace App\Controller;

use App\Form\OeuvreType;
use App\Entity\Oeuvre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Utiliser le bon namespace

final class OeuvreController extends AbstractController
{
    private $entityManager;

    // Injecter EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // #[Route('/oeuvre', name: 'app_oeuvre')]
    // public function index(Request $request): Response
    // {
    //     // Créer une nouvelle instance de l'entité Oeuvre
    //     $oeuvre = new Oeuvre();

    //     // Créer le formulaire
    //     $form = $this->createForm(OeuvreType::class, $oeuvre);

    //     // Traiter la requête du formulaire
    //     $form->handleRequest($request);

    //     // Si le formulaire est soumis et valide
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Sauvegarder l'entité dans la base de données
    //         $this->entityManager->persist($oeuvre);
    //         $this->entityManager->flush();

    //         // Afficher un message de succès ou rediriger l'utilisateur
    //         $this->addFlash('success', 'L\'oeuvre a été ajoutée avec succès!');

    //         // Rediriger après soumission
    //         return $this->redirectToRoute('app_oeuvre');
    //     }

    //     // Afficher le formulaire
    //     return $this->render('oeuvre/index.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }
}
