<?php

namespace App\Controller;
use App\Form\EventType;
use App\Entity\Event;
use App\Repository\SponsorRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\File\Exception\FileException;



#[Route('/event')]
final class EventController extends AbstractController
{
    #[Route(name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('front/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_event_new')]
    public function newEvent(Request $request, EntityManagerInterface $em, SponsorRepository $sponsorRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $user=$this->getUser();
        $event->setUserId($user->getId());
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('app_event_new');
                }
    
                $event->setImage($newFilename);
            }
    
            // Récupérer les sponsors sélectionnés dans le formulaire
            $sponsors = $form->get('sponsors')->getData();
            foreach ($sponsors as $sponsor) {
                $event->addSponsor($sponsor);
            }
    
            // Persister l'événement et les relations
            $em->persist($event);
            $em->flush();
    
            $this->addFlash('success', 'Événement ajouté avec succès !');
            return $this->redirectToRoute('app_event_index');
        }
    
        return $this->render('front/event/new.html.twig', [
            'title' => 'Ajouter un Événement',
            'form' => $form->createView(),
        ]);
    }

#[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

 if ($imageFile) {
         $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

         // Déplacer le fichier dans le dossier public/uploads/events
         try {
             $imageFile->move(
                 $this->getParameter('uploads_directoryy'), // Dossier où les images seront stockées
                 $newFilename
             );
            } catch (FileException $e) {
             $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                return $this->redirectToRoute('app_event_new');
         }

             // Mettre à jour l'entité avec le nom du fichier
             $event->setImage($newFilename);
         }
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/event/_form.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }



    #[Route('/event/details/{id}', name: 'app_event_show')]
    public function peintureDetails($id, EventRepository $eventRepository)
    {
        $event = $eventRepository->find($id);
        return $this->render('front/event/show.html.twig', [
            'event' => $event
        ]);
    }
    //  #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    //  public function showdetails(Event $event): Response
    //  {
    //      return $this->render('front/event/show.html.twig', [
    //          'event' => $event,
    //      ]);
    //  }

    /* #[Route('/remove/event{id}', name: 'app_peinture_delete')]
    public function removePeinture($id, EventRepository $peintureRepository, EntityManagerInterface $em)
    {
        $peinture = $peintureRepository->find($id);
        $em->remove($peinture);
        $em->flush();
        return $this->redirectToRoute('app_peinture_list');
    } */
    
       #[Route('/remove/{id}', name: 'app_event_delete')]
    public function delete($id,EventRepository $eventRepository, EntityManagerInterface $em){
        $event= $eventRepository->find($id);
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('app_event_index');
        //return new Response('Author deleted');
    }   
 
     #[Route('/event/{id}', name: 'event_details')]
 public function show(Event $event): Response
 {
     return $this->render('front/event/index.html.twig', [
         'event' => $event,
     ]);
 } 
    
 #[Route('/list',name: 'app_event_index_back', methods: ['GET'])]
 public function indexa(EventRepository $eventRepository): Response
 {
    $user=$this->getUser();
     return $this->render('front/event/BackEvent.html.twig', [
         'events' => $eventRepository->findAll(),
         'user'=>$user,
     ]);
 }

 #[Route('/{id}', name: 'app_event_delete_back')]
 public function deletee($id,EventRepository $eventRepository, EntityManagerInterface $em){
     $event= $eventRepository->find($id);
     $em->remove($event);
     $em->flush();
     return $this->redirectToRoute('app_event_index_back');
     //return new Response('Author deleted');
 } 


 #[Route('/{id}/editB', name: 'app_event_edit_back', methods: ['GET', 'POST'])]
    public function editt(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $user=$this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

 if ($imageFile) {
         $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

         // Déplacer le fichier dans le dossier public/uploads/events
         try {
             $imageFile->move(
                 $this->getParameter('uploads_directoryy'), // Dossier où les images seront stockées
                 $newFilename
             );
            } catch (FileException $e) {
             $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                return $this->redirectToRoute('app_event_new');
         }

             // Mettre à jour l'entité avec le nom du fichier
             $event->setImage($newFilename);
         }
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/event/editEventBack.html.twig', [
            'event' => $event,
            'form' => $form,
            'user' =>$user,
        ]);
    }

    #[Route('/back/new', name: 'app_event_new_back')]
    public function newEventt(Request $request, EntityManagerInterface $em, SponsorRepository $sponsorRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $user=$this->getUser();
        $event->setUserId($user->getId());
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('app_event_new_back');
                }
    
                $event->setImage($newFilename);
            }
    
            // Récupérer les sponsors sélectionnés dans le formulaire
            $sponsors = $form->get('sponsors')->getData();
            foreach ($sponsors as $sponsor) {
                $event->addSponsor($sponsor);
            }
    
            // Persister l'événement et les relations
            $em->persist($event);
            $em->flush();
    
            $this->addFlash('success', 'Événement ajouté avec succès !');
            return $this->redirectToRoute('app_event_index_back');
        }
    
        return $this->render('front/event/editEventBack.html.twig', [
            'title' => 'Ajouter un Événement',
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }


}
