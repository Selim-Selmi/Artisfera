<?php

namespace App\Controller;

use App\Entity\Textile;
use App\Form\TextileType;
use App\Repository\TextileRepository;
use App\Repository\CollectionTRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;



#[Route('/textile')]
final class TextileController extends AbstractController
{
    #[Route('/', name: 'app_textile_index', methods: ['GET'])]
    public function index(Request $request, TextileRepository $textileRepository, CollectionTRepository $collectionRepository): Response
    {
        $collections = $collectionRepository->findAll();
        $selectedCollectionId = $request->query->get('collection_id');
    
        $textiles = $selectedCollectionId
        ? $textileRepository->findByCollection($selectedCollectionId)
        : $textileRepository->findAll();
    
    
        return $this->render('textile/index.html.twig', [
            'textiles' => $textiles,
            'collections' => $collections,
            'selectedCollection' => $selectedCollectionId ? $collectionRepository->find($selectedCollectionId) : null,
        ]);
    }

    #[Route('/new', name: 'app_textile_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, ParameterBagInterface $params): Response
    {
        $textile = new Textile();
        $form = $this->createForm(TextileType::class, $textile);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();
    
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                // Use the correct uploads directory from services.yaml
                $targetDirectory = $params->get('uploads_directory');
    
                // Ensure the directory exists
                if (!file_exists($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }
    
                try {
                    $imageFile->move($targetDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error uploading file: ' . $e->getMessage());
                    return $this->redirectToRoute('app_textile_new');
                }
    
                $textile->setImage('/uploads/images/' . $newFilename);
            } else {
                $this->addFlash('error', 'Please upload an image.');
                return $this->redirectToRoute('app_textile_new');
            }
    
            $entityManager->persist($textile);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_textile_index');
        }
    
        return $this->render('textile/new.html.twig', [
            'textile' => $textile,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_textile_show', methods: ['GET'])]
    public function show(Textile $textile): Response
    {
        return $this->render('textile/show.html.twig', [
            'textile' => $textile,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_textile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Textile $textile, EntityManagerInterface $entityManager, SluggerInterface $slugger, ParameterBagInterface $params): Response
    {
        $form = $this->createForm(TextileType::class, $textile);
        $form->handleRequest($request);
    
        $currentImage = $textile->getImage(); // Save the current image in case the user does not upload a new one
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();
    
            if ($imageFile) {
                // Handle the new image upload
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                // Use the correct uploads directory from services.yaml
                $targetDirectory = $params->get('uploads_directory');
    
                // Ensure the directory exists
                if (!file_exists($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }
    
                try {
                    $imageFile->move($targetDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error uploading file: ' . $e->getMessage());
                    return $this->redirectToRoute('app_textile_edit', ['id' => $textile->getId()]);
                }
    
                $textile->setImage('/uploads/images/' . $newFilename);
    
                // Delete the old image if a new one is uploaded (optional)
                if ($currentImage && file_exists($params->get('uploads_directory') . '/' . basename($currentImage))) {
                    unlink($params->get('uploads_directory') . '/' . basename($currentImage));
                }
            } else {
                // If no new image was uploaded, keep the old one
                $textile->setImage($currentImage);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_textile_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('textile/edit.html.twig', [
            'textile' => $textile,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/{id}', name: 'app_textile_delete', methods: ['POST'])]
    public function delete(Request $request, Textile $textile, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$textile->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($textile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_textile_index', [], Response::HTTP_SEE_OTHER);
    }
}
