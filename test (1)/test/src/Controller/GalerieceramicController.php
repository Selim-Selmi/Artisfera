<?php

namespace App\Controller;

use App\Form\OeuvreType;
use App\Entity\Oeuvre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\OeuvreRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class GalerieceramicController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //ajout d un oeuvre
    #[Route('/ajout-oeuvre', name: 'app_ajout_oeuvre')]
public function ajoutOeuvre(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator ): Response
{
    $oeuvre = new Oeuvre();
    $user=$this->getUser();
    $oeuvre->setUserId($user->getId());
    $form = $this->createForm(OeuvreType::class, $oeuvre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            $imageFile->move($destination, $newFilename);

            $oeuvre->setImage($newFilename);
        }

        $entityManager->persist($oeuvre);
        $entityManager->flush();

        $this->addFlash('success', 'L\'œuvre a été ajoutée avec succès!');
        return $this->redirectToRoute('app_galerieceramic');
    }

    return $this->render('galerieceramic/ajout.html.twig', [
        'form' => $form->createView(),
        'user'=>$user
    ]);
}

//search function 






//details
    #[Route('/galerieceramic/oeuvre/{id}', name: 'app_oeuvre_details')]
    public function details(int $id): Response
    {
        $oeuvre = $this->entityManager->getRepository(Oeuvre::class)->find($id);
    
        if (!$oeuvre) {
            throw $this->createNotFoundException("L'œuvre demandée n'existe pas.");
        }
    
        return $this->render('galerieceramic/index.html.twig', [
            'oeuvre' => $oeuvre, // On passe l'œuvre sélectionnée au template
        ]);
    }

    

//show  for artist
    #[Route('/galerieceramic', name: 'app_galerieceramic', methods: ['GET'])]
    public function galerie(EntityManagerInterface $entityManager): Response
    {
        $oeuvres = $entityManager->getRepository(Oeuvre::class)->findAll();
        $user=$this->getUser();
        return $this->render('galerieceramic/index.html.twig', [
            'oeuvres' => $oeuvres, // On passe bien 'oeuvres' à Twig
            'user' => $user
        ]);
    }

    //show for members
    #[Route('/galeriemembers', name: 'app_galeriemembers', methods: ['GET'])]
    public function galerieForMembers(EntityManagerInterface $entityManager): Response
    {
        // Retrieve the artworks for the gallery (can be the same or a filtered list)
        $oeuvres = $entityManager->getRepository(Oeuvre::class)->findAll();
    
        return $this->render('galerieceramic/galerie.html.twig', [
            'oeuvres' => $oeuvres, // Pass the artworks to the Twig template
        ]);
    }
    
    
//delete

    #[Route('/delete/{id}', name: 'app_oeuvre_delete')]
    public function delete($id, EntityManagerInterface $em, OeuvreRepository $oeuvreRepository): Response
    {
        $oeuvre= $oeuvreRepository->find($id);
        $em->remove($oeuvre);
        $em->flush();
        return $this->redirectToRoute('app_galerieceramic');
        
    }

//edit
#[Route('/edit-oeuvre/{id}', name: 'app_edit_oeuvre', methods: ['GET', 'POST'])]
public function editOeuvre(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager, SluggerInterface $slugger, ParameterBagInterface $params): Response
{
    // Create the form and handle the request
    $form = $this->createForm(OeuvreType::class, $oeuvre);
    $form->handleRequest($request);

    // Save the current image path
    $currentImage = $oeuvre->getImage();

    if ($form->isSubmitted()) {
        if ($form->isValid()) {
            // Debug: Check the entity state before changes
            dump('Before changes:', $oeuvre);

            // Handle image upload
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Generate a unique filename
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $targetDirectory = $params->get('uploads_images_directory');

                // Ensure the target directory exists
                if (!file_exists($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }

                try {
                    // Move the uploaded file to the target directory
                    $imageFile->move($targetDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement du fichier : ' . $e->getMessage());
                    return $this->redirectToRoute('app_edit_oeuvre', ['id' => $oeuvre->getId()]);
                }

                // Set the new image path (relative to the public directory)
                $oeuvre->setImage('uploads/images/' . $newFilename);

                // Delete the old image if it exists
                if ($currentImage && file_exists($params->get('uploads_images_directory') . '/' . basename($currentImage))) {
                    unlink($params->get('uploads_images_directory') . '/' . basename($currentImage));
                }
            } else {
                // Keep the old image if no new image is uploaded
                $oeuvre->setImage($currentImage);
            }

            // Debug: Check the entity state after changes
            dump('After changes:', $oeuvre);

            // Persist changes to the database
            $entityManager->flush();

            // Add a success flash message
            $this->addFlash('success', 'L\'œuvre a été modifiée avec succès!');

            // Redirect to the gallery page
            return $this->redirectToRoute('app_galerieceramic');
        } else {
            // Debug: Display form validation errors
            dump('Form errors:', $form->getErrors(true, false));
        }
    }

    // Render the edit form template
    return $this->render('galerieceramic/edit.html.twig', [
        'oeuvre' => $oeuvre,
        'form' => $form->createView(),
    ]);
}
//afficher back
#[Route('/galerieceramic/back', name: 'app_galerieceramic_back', methods: ['GET'])]
public function galerieb(EntityManagerInterface $entityManager): Response
{
    $oeuvres = $entityManager->getRepository(Oeuvre::class)->findAll();
    $user=$this->getUser();
    return $this->render('galerieceramic/BackCeramic.html.twig', [
        'oeuvres' => $oeuvres, // On passe bien 'oeuvres' à Twig
        'user' => $user
    ]);
}

#[Route('/ajout-oeuvre/back', name: 'app_ajout_oeuvre_back')]
public function ajoutOeuvree(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator ): Response
{
    $oeuvre = new Oeuvre();
    $user=$this->getUser();
    $oeuvre->setUserId($user->getId());
    $form = $this->createForm(OeuvreType::class, $oeuvre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            $imageFile->move($destination, $newFilename);

            $oeuvre->setImage($newFilename);
        }

        $entityManager->persist($oeuvre);
        $entityManager->flush();

        $this->addFlash('success', 'L\'œuvre a été ajoutée avec succès!');
        return $this->redirectToRoute('app_galerieceramic_back');
    }

    return $this->render('galerieceramic/editCeramicBack.html.twig', [
        'form' => $form->createView(),
        'user'=>$user
    ]);
}

#[Route('/edit-oeuvre/back/{id}', name: 'app_edit_oeuvre_back', methods: ['GET', 'POST'])]
public function editOeuvree(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager, SluggerInterface $slugger, ParameterBagInterface $params): Response
{
    // Create the form and handle the request
    $form = $this->createForm(OeuvreType::class, $oeuvre);
    $form->handleRequest($request);
    $user=$this->getUser();
    // Save the current image path
    $currentImage = $oeuvre->getImage();

    if ($form->isSubmitted()) {
        if ($form->isValid()) {
            // Debug: Check the entity state before changes
            dump('Before changes:', $oeuvre);

            // Handle image upload
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Generate a unique filename
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $targetDirectory = $params->get('uploads_images_directory');

                // Ensure the target directory exists
                if (!file_exists($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }

                try {
                    // Move the uploaded file to the target directory
                    $imageFile->move($targetDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement du fichier : ' . $e->getMessage());
                    return $this->redirectToRoute('app_edit_oeuvre_back', ['id' => $oeuvre->getId()]);
                }

                // Set the new image path (relative to the public directory)
                $oeuvre->setImage('uploads/images/' . $newFilename);

                // Delete the old image if it exists
                if ($currentImage && file_exists($params->get('uploads_images_directory') . '/' . basename($currentImage))) {
                    unlink($params->get('uploads_images_directory') . '/' . basename($currentImage));
                }
            } else {
                // Keep the old image if no new image is uploaded
                $oeuvre->setImage($currentImage);
            }

            // Debug: Check the entity state after changes
            dump('After changes:', $oeuvre);

            // Persist changes to the database
            $entityManager->flush();

            // Add a success flash message
            $this->addFlash('success', 'L\'œuvre a été modifiée avec succès!');

            // Redirect to the gallery page
            return $this->redirectToRoute('app_galerieceramic_back');
        } else {
            // Debug: Display form validation errors
            dump('Form errors:', $form->getErrors(true, false));
        }
    }

    // Render the edit form template
    return $this->render('galerieceramic/editCeramicBack.html.twig', [
        'oeuvre' => $oeuvre,
        'form' => $form->createView(),
        'user' =>$user
    ]);

}

#[Route('//back/delete/{id}', name: 'app_oeuvre_delete_back')]
    public function deletee($id, EntityManagerInterface $em, OeuvreRepository $oeuvreRepository): Response
    {
        $oeuvre= $oeuvreRepository->find($id);
        $user=$this->getUser();
        $em->remove($oeuvre);
        $em->flush();
        return $this->redirectToRoute('app_galerieceramic_back');
        
    }

}
