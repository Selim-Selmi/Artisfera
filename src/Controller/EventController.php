<?php

namespace App\Controller;
use App\Form\EventType;
use App\Entity\Event;
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
public function newEvent(Request $request, EntityManagerInterface $em): Response
{
    
    $event = new Event();
    $form = $this->createForm(EventType::class, $event);
    $form->handleRequest($request);

     if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier image téléchargé
        $imageFile = $form->get('image')->getData();

 if ($imageFile) {
         $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

         // Déplacer le fichier dans le dossier public/uploads/events
         try {
             $imageFile->move(
                 $this->getParameter('uploads_directory'), // Dossier où les images seront stockées
                 $newFilename
             );
            } catch (FileException $e) {
             $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                return $this->redirectToRoute('app_event_new');
         }

             // Mettre à jour l'entité avec le nom du fichier
             $event->setImage($newFilename);
         }
        
        $em->persist($event);
        $em->flush();

        $this->addFlash('success', 'Événement ajouté avec succès !');
        return $this->redirectToRoute('app_event_index');
    }

    return $this->render('front/event/new.html.twig', [
        'title' => 'Ajouter un Événement',
        'form' => $form->createView(),  // Passer la variable form au template
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
                 $this->getParameter('uploads_directory'), // Dossier où les images seront stockées
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

    
 #[Route('/{id}', name: 'app_event_delete')]
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

    
}
