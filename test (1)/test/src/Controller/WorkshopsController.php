<?php

namespace App\Controller;

use App\Entity\Workshops;
use App\Form\WorkshopsType;
use App\Repository\WorkshopsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;


#[Route('/workshops')]
final class WorkshopsController extends AbstractController{
    #[Route(name: 'app_workshops_index', methods: ['GET'])]
    public function index(WorkshopsRepository $workshopsRepository): Response
    {
        return $this->render('workshops/index.html.twig', [
            'workshops' => $workshopsRepository->findAll(),
        ]);
    }

  
    #[Route('/new', name: 'app_workshops_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $workshop = new Workshops();
    $form = $this->createForm(WorkshopsType::class, $workshop);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier vidéo
        $videoFile = $form->get('video')->getData();

        if ($videoFile) {
            // Vérifier l'extension du fichier
            $allowedMimeTypes = ['video/mp4', 'audio/mpeg'];
            if (!in_array($videoFile->getMimeType(), $allowedMimeTypes)) {
                $this->addFlash('error', 'Format de fichier non valide. Veuillez télécharger un fichier MP4 ou MP3.');
                return $this->redirectToRoute('app_workshops_new');
            }

            // Générer un nom unique pour la vidéo
            $newFilename = uniqid() . '.' . $videoFile->guessExtension();

            try {
                // Déplacer la vidéo dans le dossier défini dans services.yaml
                $videoFile->move(
                    $this->getParameter('video_directory'),
                    $newFilename
                );
                $workshop->setVideo($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                return $this->redirectToRoute('app_workshops_new');
            }
        }

        $entityManager->persist($workshop);
        $entityManager->flush();

        return $this->redirectToRoute('app_workshops_index');
    }

    return $this->render('workshops/new.html.twig', [
        'workshop' => $workshop,
        'form' => $form,
    ]);
}


   

    #[Route('/{id}', name: 'app_workshops_show', methods: ['GET'])]
    public function show(Workshops $workshop): Response
    {
        return $this->render('workshops/show.html.twig', [
            'workshop' => $workshop,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_workshops_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Workshops $workshop, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WorkshopsType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_workshops_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('workshops/edit.html.twig', [
            'workshop' => $workshop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_workshops_delete', methods: ['POST'])]
    public function delete(Request $request, Workshops $workshop, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workshop->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($workshop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_workshops_index', [], Response::HTTP_SEE_OTHER);
    }
}
