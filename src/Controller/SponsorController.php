<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\SponsorType;
use App\Repository\SponsorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sponsor')]
final class SponsorController extends AbstractController
{
    #[Route(name: 'app_sponsor_index', methods: ['GET'])]
    public function index(SponsorRepository $sponsorRepository): Response
    {
        return $this->render('front/sponsor/index.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sponsor_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $sponsor = new Sponsor();
    $form = $this->createForm(SponsorType::class, $sponsor);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier image téléchargé
        $logoFile = $form->get('logo')->getData();

        if ($logoFile) {
            $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();

            // Déplacer le fichier dans le dossier public/uploads/sponsors
            try {
                $logoFile->move(
                    $this->getParameter('uploads_directory') . '/sponsors', // Dossier où les logos seront stockés
                    $newFilename
                );
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload du logo.');
                return $this->redirectToRoute('app_sponsor_new');
            }

            // Mettre à jour l'entité avec le nom du fichier
            $sponsor->setLogo($newFilename);
        }

        // Sauvegarder l'entité sponsor
        $entityManager->persist($sponsor);
        $entityManager->flush();

        $this->addFlash('success', 'Sponsor ajouté avec succès !');
        return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('front/sponsor/new.html.twig', [
        'sponsor' => $sponsor,
        'form' => $form->createView(),
    ]);
}

    // #[Route('/{id}', name: 'app_sponsor_show', methods: ['GET'])]
    // public function show(Sponsor $sponsor): Response
    // {
    //     return $this->render('front/sponsor/show.html.twig', [
    //         'sponsor' => $sponsor,
    //     ]);
    // }

    // 
    
#[Route('/{id}/edit', name: 'app_sponsor_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Sponsor $sponsor, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(SponsorType::class, $sponsor);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $logoFile = $form->get('logo')->getData();

if ($logoFile) {
     $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
    $newFilename = $originalFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();

     // Déplacer le fichier dans le dossier public/uploads/events
     try {
         $logoFile->move(
             $this->getParameter('uploads_directory'), // Dossier où les images seront stockées
             $newFilename
         );
        } catch (FileException $e) {
         $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
            return $this->redirectToRoute('app_sponsor_new');
     }

         // Mettre à jour l'entité avec le nom du fichier
         $sponsor->setLogo($newFilename);
     }
        $entityManager->flush();

        return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('front/sponsor/_form.html.twig', [
        'sponsor' => $sponsor,
        'form' => $form,
    ]);
}


  
    #[Route('/{id}', name: 'app_sponsor_delete')]
    public function delete($id,SponsorRepository $sponsorRepository, EntityManagerInterface $em){
        $sponsor= $sponsorRepository->find($id);
        $em->remove($sponsor);
        $em->flush();
        return $this->redirectToRoute('app_sponsor_index');
        //return new Response('Author deleted');
    }
}
