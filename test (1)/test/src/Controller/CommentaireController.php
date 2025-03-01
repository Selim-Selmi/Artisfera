<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Oeuvre;

use Symfony\Component\Security\Core\Security;


#[Route('/commentaire')]
final class CommentaireController extends AbstractController{
    #[Route(name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    

// #[Route('/new/{oeuvreId}', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
// public function new(int $oeuvreId, Request $request, EntityManagerInterface $entityManager, Security $security): Response
// {
//     // Créer une nouvelle instance de Commentaire
//     $commentaire = new Commentaire();

//     // Récupérer l'œuvre avec l'ID passé dans la route
//     $oeuvre = $entityManager->getRepository(Oeuvre::class)->find($oeuvreId);

//     if (!$oeuvre) {
//         throw $this->createNotFoundException('L\'œuvre spécifiée n\'existe pas.');
//     }

//     // Associer l'œuvre au commentaire
//     $commentaire->setOeuvre($oeuvre);

//     // Créer et traiter le formulaire
//     $form = $this->createForm(CommentaireType::class, $commentaire);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         // Récupérer l'utilisateur connecté
//         $user = $security->getUser();

//         if ($user) {
//             $commentaire->setUser($user); // Associer le commentaire à l'utilisateur connecté
//         }

//         // Persister le commentaire dans la base de données
//         $entityManager->persist($commentaire);
//         $entityManager->flush();

//         // Rediriger vers la page d'index des commentaires
//         return $this->redirectToRoute('app_galerieceramic', [], Response::HTTP_SEE_OTHER);
//     }

//     return $this->render('commentaire/new.html.twig', [
//         'commentaire' => $commentaire,
//         'form' => $form,
//         'oeuvre' => $oeuvre,
//     ]);
// }

// #[Route('/new/{oeuvreId}', name: 'app_commentaire_new', methods: ['POST'])]
// public function new(int $oeuvreId, Request $request, EntityManagerInterface $entityManager, Security $security): Response
// {
//     // Create a new Commentaire instance
//     $commentaire = new Commentaire();

//     // Retrieve the artwork by ID
//     $oeuvre = $entityManager->getRepository(Oeuvre::class)->find($oeuvreId);

//     if (!$oeuvre) {
//         throw $this->createNotFoundException('L\'œuvre spécifiée n\'existe pas.');
//     }

//     // Associate the artwork with the comment
//     $commentaire->setOeuvre($oeuvre);

//     // Create and handle the form
//     $form = $this->createForm(CommentaireType::class, $commentaire);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         // Get the logged-in user
//         $user = $security->getUser();

//         if ($user) {
//             $commentaire->setUser($user); // Associate the comment with the logged-in user
//         }

//         // Persist the comment to the database
//         $entityManager->persist($commentaire);
//         $entityManager->flush();

//         // Return the new comment as a JSON response (AJAX)
//         return $this->json([
//             'status' => 'success',
//             'comment' => [
//                 'user' => $commentaire->getUser()->getNom(),
//                 'contenu' => $commentaire->getContenu(),
//                 'date' => $commentaire->getDate()->format('d-m-Y H:i'),
//             ],
//         ]);
//     }

//     return $this->json([
//         'status' => 'error',
//         'message' => 'Le formulaire est invalide.',
//     ]);
// }
// #[Route('/new/{oeuvreId}', name: 'app_commentaire_new', methods: ['POST'])]
// public function new(int $oeuvreId, Request $request, EntityManagerInterface $entityManager, Security $security): Response
// {
//     // Create a new Commentaire instance
//     $commentaire = new Commentaire();

//     // Retrieve the artwork by ID
//     $oeuvre = $entityManager->getRepository(Oeuvre::class)->find($oeuvreId);

//     // Check if the artwork exists
//     if (!$oeuvre) {
//         // If the artwork is not found, return a JSON response with an error message
//         return $this->json([
//             'status' => 'error',
//             'message' => 'L\'œuvre spécifiée n\'existe pas.'
//         ], Response::HTTP_NOT_FOUND); // HTTP 404
//     }

//     // Associate the artwork with the comment
//     $commentaire->setOeuvre($oeuvre);

//     // Create and handle the form
//     $form = $this->createForm(CommentaireType::class, $commentaire);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         // Get the logged-in user
//         $user = $security->getUser();

//         if ($user) {
//             $commentaire->setUser($user); // Associate the comment with the logged-in user
//         }

//         // Persist the comment to the database
//         $entityManager->persist($commentaire);
//         $entityManager->flush();

//         // Return the new comment as a JSON response (AJAX)
//         return $this->json([
//             'status' => 'success',
//             'comment' => [
//                 'user' => $commentaire->getUser()->getNom(),
//                 'contenu' => $commentaire->getContenu(),
//                 'date' => $commentaire->getDate()->format('d-m-Y H:i'),
//             ],
//         ]);
//     }

//     return $this->json([
//         'status' => 'error',
//         'message' => 'Le formulaire est invalide.',
//     ]);
// }
#[Route('/new/{oeuvreId}', name: 'app_commentaire_new', methods: ['POST'])]
public function new(int $oeuvreId, Request $request, EntityManagerInterface $entityManager, Security $security): Response
{
    // Retrieve the artwork by ID
    $oeuvre = $entityManager->getRepository(Oeuvre::class)->find($oeuvreId);

    // Check if the artwork exists
    if (!$oeuvre) {
        return $this->json([
            'status' => 'error',
            'message' => 'L\'œuvre spécifiée n\'existe pas.'
        ], Response::HTTP_NOT_FOUND); // HTTP 404
    }

    // Create a new Commentaire instance
    $commentaire = new Commentaire();
    $contenu = $request->request->get('contenu'); // Get the 'contenu' from the POST request

    // Check if the 'contenu' is empty
    if (empty($contenu)) {
        return $this->json([
            'status' => 'error',
            'message' => 'Le contenu du commentaire est obligatoire.'
        ], Response::HTTP_BAD_REQUEST); // HTTP 400
    }

    // Associate the artwork with the comment
    $commentaire->setOeuvre($oeuvre);
    $commentaire->setContenu($contenu); // Set the content of the comment

    // Get the logged-in user
    $user = $security->getUser();
    if ($user) {
        $commentaire->setUser($user); // Associate the comment with the logged-in user
    }

    // Persist the comment to the database
    $entityManager->persist($commentaire);
    $entityManager->flush();

    // Return the new comment as a JSON response (AJAX)
    return $this->json([
        'status' => 'success',
        'comment' => [
            'user' => $commentaire->getUser() ? $commentaire->getUser()->getNom() : 'Utilisateur inconnu',
            'contenu' => $commentaire->getContenu(),
            'date' => $commentaire->getDate()->format('d-m-Y H:i'),
        ],
    ]);
}



    
    //route jdida 
    #[Route('/oeuvre/{oeuvreId}', name: 'app_commentaire_show', methods: ['GET'])]
public function show(int $oeuvreId, EntityManagerInterface $entityManager): Response
{
    // Retrieve the oeuvre
    $oeuvre = $entityManager->getRepository(Oeuvre::class)->find($oeuvreId);

    if (!$oeuvre) {
        throw $this->createNotFoundException('L\'œuvre spécifiée n\'existe pas.');
    }

    // Retrieve all comments related to this oeuvre
    $commentaires = $entityManager->getRepository(Commentaire::class)->findBy(['oeuvre' => $oeuvre]);

    return $this->render('commentaire/show.html.twig', [
        'oeuvre' => $oeuvre,
        'commentaires' => $commentaires,
    ]);
}





    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
