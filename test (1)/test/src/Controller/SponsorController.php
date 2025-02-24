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
    $user=$this->getUser();
    $sponsor->setUserId($user->getId());

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
        'user'=>$user,
    ]);
}


    #[Route('/event/{id}/sponsors', name: 'app_event_sponsors', methods: ['GET'])]
    public function showSponsors(Event $event): Response
    {
        return $this->render('front/event/sponsors.html.twig', [
            'event' => $event,
            'sponsors' => $event->getSponsors(),
        ]);
    }
    
    public function addSponsor(Sponsor $sponsor): static
    {
        if (!$this->sponsors->contains($sponsor)) {
            $this->sponsors->add($sponsor);
            $sponsor->addEvenement($this); // Mise à jour de la relation bidirectionnelle
        }
        return $this;
    }
    
    public function removeSponsor(Sponsor $sponsor): static
    {
        if ($this->sponsors->removeElement($sponsor)) {
            $sponsor->removeEvenement($this);
        }
        return $this;
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
             $this->getParameter('uploads_directoryy'), // Dossier où les images seront stockées
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
  
      #[Route('removee/{id}', name: 'app_sponsor_delete')]
    public function deletee($id,SponsorRepository $sponsorRepository, EntityManagerInterface $em){
        $sponsor= $sponsorRepository->find($id);
        $em->remove($sponsor);
        $em->flush();
        return $this->redirectToRoute('app_sponsor_index');
        //return new Response('Author deleted');
    }  

    #[Route('/list',name: 'app_sponsor_index_back', methods: ['GET'])]
 public function indexa(SponsorRepository $sponsorRepository): Response
 {
    $user=$this->getUser();
     return $this->render('front/sponsor/BackSponsor.html.twig', [
         'sponsors' => $sponsorRepository->findAll(),
         'user'=>$user,
     ]);
 }

 #[Route('/{id}', name: 'app_sponsor_delete_back')]
 public function delete($id,SponsorRepository $sponsorRepository, EntityManagerInterface $em){
     $sponsor= $sponsorRepository->find($id);
     $em->remove($sponsor);
     $em->flush();
     return $this->redirectToRoute('app_sponsor_index_back');
     //return new Response('Author deleted');
 }

 #[Route('/{id}/editS', name: 'app_sponsor_edit_back', methods: ['GET', 'POST'])]
 public function editt(Request $request, Sponsor $sponsor, EntityManagerInterface $entityManager): Response
 {
     $form = $this->createForm(SponsorType::class, $sponsor);
     $form->handleRequest($request);
     $user=$this->getUser();
 
     if ($form->isSubmitted() && $form->isValid()) {
         $logoFile = $form->get('logo')->getData();
 
 if ($logoFile) {
      $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
     $newFilename = $originalFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();
 
      // Déplacer le fichier dans le dossier public/uploads/events
      try {
          $logoFile->move(
              $this->getParameter('uploads_directoryy'), // Dossier où les images seront stockées
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
 
         return $this->redirectToRoute('app_sponsor_index_back', [], Response::HTTP_SEE_OTHER);
     }
 
     return $this->render('front/sponsor/editSponsorBack.html.twig', [
         'sponsor' => $sponsor,
         'form' => $form,
         'user'=>$user,
     ]);
 }

 #[Route('/back/new', name: 'app_sponsor_new_back', methods: ['GET', 'POST'])]
public function neww(Request $request, EntityManagerInterface $entityManager): Response
{
    $sponsor = new Sponsor();
    $form = $this->createForm(SponsorType::class, $sponsor);
    $form->handleRequest($request);
    $user=$this->getUser();
    $sponsor->setUserId($user->getId());

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
                return $this->redirectToRoute('app_sponsor_new_back');
            }

            // Mettre à jour l'entité avec le nom du fichier
            $sponsor->setLogo($newFilename);
        }

        // Sauvegarder l'entité sponsor
        $entityManager->persist($sponsor);
        $entityManager->flush();

        $this->addFlash('success', 'Sponsor ajouté avec succès !');
        return $this->redirectToRoute('app_sponsor_index_back', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('front/sponsor/editSponsorBack.html.twig', [
        'sponsor' => $sponsor,
        'form' => $form->createView(),
        'user'=>$user,
    ]);
}
 
}
