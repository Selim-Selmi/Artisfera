<?php

namespace App\Controller;

use App\Entity\Peinture;
use App\Form\AddEditPeintureType;
use App\Repository\PeintureRepository;
use App\Repository\StyleRepository;
use App\Repository\MusiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Attribute\Route;

final class PeintureController extends AbstractController
{
    #[Route('/peinture', name: 'app_peinture')]
    public function index(): Response
    {
        return $this->render('peinture/index.html.twig', [
            'controller_name' => 'PeintureController',
        ]);
    }

    #[Route('/peinture/new', name: 'app_peinture_new')]
    public function newPeinture(Request $request, EntityManagerInterface $em)
    {
        $peinture = new Peinture();
        $user=$this->getUser();
        $peinture->setUserId($user->getId());
        $form = $this->createForm(AddEditPeintureType::class, $peinture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier téléchargé
            $tableauFile = $form->get('tableau')->getData();

            if ($tableauFile) {
                $originalFilename = pathinfo($tableauFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$tableauFile->guessExtension();

                // Déplacer le fichier dans le dossier public/uploads/peintures
                try {
                    $tableauFile->move(
                        $this->getParameter('uploads_directory'), // Dossier où les images seront stockées
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('app_peinture_new');
                }

                // Mettre à jour l'entité avec le nom du fichier
                $peinture->setTableau($newFilename);
            }

            $em->persist($peinture);
            $em->flush();

            return $this->redirectToRoute('app_peinture_list');
        }

        return $this->render('peinture/form.html.twig', [
            'title' => 'Ajouter une Peinture',
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/peinture/edit/{id}', name: 'app_peinture_edit')]
    public function editPeinture($id, Request $request, EntityManagerInterface $em, PeintureRepository $peintureRepository)
    {
        $peinture = $peintureRepository->find($id);
        $form = $this->createForm(AddEditPeintureType::class, $peinture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload du fichier si un nouveau fichier est sélectionné
            $tableauFile = $form->get('tableau')->getData();

            if ($tableauFile) {
                $originalFilename = pathinfo($tableauFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$tableauFile->guessExtension();

                // Déplacer le fichier dans le dossier public/uploads/peintures
                try {
                    $tableauFile->move(
                        $this->getParameter('uploads_directory'), // Dossier où les images seront stockées
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('app_peinture_edit', ['id' => $peinture->getId()]);
                }

                // Mettre à jour l'entité avec le nom du fichier
                $peinture->setTableau($newFilename);
            }

            $em->flush();

            return $this->redirectToRoute('app_peinture_list');
        }

        return $this->render('peinture/form.html.twig', [
            'title' => 'Modifier une Peinture',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/remove/peinture{id}', name: 'app_peinture_remove')]
    public function removePeinture($id, PeintureRepository $peintureRepository, EntityManagerInterface $em)
    {
        $peinture = $peintureRepository->find($id);
        $em->remove($peinture);
        $em->flush();
        return $this->redirectToRoute('app_peinture_list');
    }

    #[Route('list/peinture', name: 'app_peinture_list')]
    public function listPeinture(PeintureRepository $peintureRepository)
    {
        $peinturesDB = $peintureRepository->findAll();
        return $this->render('peinture/list.html.twig', [
            'peintures' => $peinturesDB
        ]);
    }
    #[Route('/details/peinture/{id}', name: 'app_peinture_details')]
    public function peintureDetails($id, PeintureRepository $peintureRepository)
    {
        $peintureDB = $peintureRepository->find($id);
        return $this->render('peinture/details.html.twig', [
            'peinture' => $peintureDB
        ]);
    }
    #[Route('/edit/peinture/{id}', name: 'app_peinture_edit_back')]
    public function editePeinture($id, Request $request, EntityManagerInterface $em, PeintureRepository $peintureRepository)
    {
        $us=$this->getUser();
        $peinture = $peintureRepository->find($id);
        $form = $this->createForm(AddEditPeintureType::class, $peinture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload du fichier si un nouveau fichier est sélectionné
            $tableauFile = $form->get('tableau')->getData();

            if ($tableauFile) {
                $originalFilename = pathinfo($tableauFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$tableauFile->guessExtension();

                // Déplacer le fichier dans le dossier public/uploads/peintures
                try {
                    $tableauFile->move(
                        $this->getParameter('uploads_directory'), // Dossier où les images seront stockées
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('app_peinture_edit', ['id' => $peinture->getId()]);
                }

                // Mettre à jour l'entité avec le nom du fichier
                $peinture->setTableau($newFilename);
            }

            $em->flush();

            return $this->redirectToRoute('app_peinture_list_back');
        }

        return $this->render('peinture/editPeintureBack.html.twig', [
            'title' => 'Modifier une Peinture',
            'form' => $form->createView(),
            'user'=>$us

        ]);
    }
    #[Route('/list/back/peinture', name: 'app_peinture_list_back')]
    public function peinture(PeintureRepository $peintureRepository,StyleRepository $styleRepository){
        $peintureBD = $peintureRepository->findAll();
        $us=$this->getUser();
        return $this->render('peinture/BackPeinture.html.twig',[
            'peintures' => $peintureBD ,
            'user'=>$us
        ]);
    }

    #[Route('/remove/back/peinture{id}', name: 'app_peinture_remove_back')]
    public function removePeinturee($id, PeintureRepository $peintureRepository, EntityManagerInterface $em)
    {
        $peinture = $peintureRepository->find($id);
        $em->remove($peinture);
        $em->flush();
        return $this->redirectToRoute('app_peinture_list_back');
    }

    #[Route('/new/peinture', name: 'app_peinture_new_back')]
    public function newPeinturee(Request $request, EntityManagerInterface $em)
    {
        $peinture = new Peinture();
        $user=$this->getUser();
        $peinture->setUserId($user->getId());
        $form = $this->createForm(AddEditPeintureType::class, $peinture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier téléchargé
            $tableauFile = $form->get('tableau')->getData();

            if ($tableauFile) {
                $originalFilename = pathinfo($tableauFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$tableauFile->guessExtension();

                // Déplacer le fichier dans le dossier public/uploads/peintures
                try {
                    $tableauFile->move(
                        $this->getParameter('uploads_directory'), // Dossier où les images seront stockées
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('app_peinture_new_back');
                }

                // Mettre à jour l'entité avec le nom du fichier
                $peinture->setTableau($newFilename);
            }

            $em->persist($peinture);
            $em->flush();

            return $this->redirectToRoute('app_peinture_list_back');
        }

        return $this->render('peinture/editPeintureBack.html.twig', [
            'title' => 'Ajouter une Peinture',
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

   

}


