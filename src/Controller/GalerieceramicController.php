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
    ]);
}

    






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
    
        return $this->render('galerieceramic/index.html.twig', [
            'oeuvres' => $oeuvres, // On passe bien 'oeuvres' à Twig
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
    #[Route('/edit-oeuvre/{id}', name: 'app_edit_oeuvre')]
    public function editOeuvre(Oeuvre $oeuvre, Request $request, EntityManagerInterface $entityManager): Response
    {
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
    
            $entityManager->flush();
    
            $this->addFlash('success', 'L\'œuvre a été modifiée avec succès!');
            return $this->redirectToRoute('app_galerieceramic');
        }
    
        return $this->render('galerieceramic/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    


}
