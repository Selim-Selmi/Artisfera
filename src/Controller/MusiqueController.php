<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Musique;
use App\Form\AddEditMusiqueType;
use App\Form\EditMusiqueType;
use App\Repository\MusiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Playlist;
use App\Form\AddEditPlaylistType;
use App\Repository\PlaylistRepository;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class MusiqueController extends AbstractController
{

    #[Route('/musique', name: 'app_musique')]
    public function index(): Response
    {
        return $this->render('musique/index.html.twig', [
            'controller_name' => 'MusiqueController',
        ]);
    }


       #[Route('/Musique/add', name: 'app_Musique_new')]
    public function newMusique(Request $request, EntityManagerInterface $em)
    {
    $musique = new Musique();
    $form = $this->createForm(AddEditMusiqueType::class, $musique);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
     
        $cheminFichier = $form->get('cheminFichier')->getData();
        if ($cheminFichier ) {
             $originalFilename = pathinfo($cheminFichier->getClientOriginalName(), PATHINFO_FILENAME);
             $filename =$originalFilename.'-'.uniqid() . '.' . $cheminFichier->guessExtension();
            $cheminFichier->move(
                $this->getParameter('musique_files_directory'), // Ensure this directory is defined in services.yaml
                $filename
            );
            $musique->setCheminFichier($filename); // Save the file path in the database
        }
        

        // Handle file upload for photo
         $photo = $form->get('photo')->getData();
        if ($photo) {
            $originalFilename1 = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            $photoFilename =$originalFilename1.'-'.uniqid().'.'.$photo->guessExtension();
            $photo->move(
                $this->getParameter('photo_files_directory'),
                $photoFilename
            );
            $musique->setPhoto($photoFilename);
        }
        
        $musique->setDateSortie(new \DateTime());
        $em->persist($musique);
        $em->flush();

        return $this->redirectToRoute('app_Musique_list');
    }

    return $this->render('Musique/form.html.twig', [
        'title' => 'Add Musique',
        'form' => $form->createView(),
    ]);
}  

    


#[Route('/Musique/edit/{id}', name: 'app_Musique_edit')]
public function editMusique($id, Request $request, EntityManagerInterface $em, MusiqueRepository $MusiqueRepository)
{
    $musique = $MusiqueRepository->find($id);
    if (!$musique) {
        throw $this->createNotFoundException('Music not found');
    }

    $form = $this->createForm(AddEditMusiqueType::class, $musique);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload for the music file
        $cheminFichier = $form->get('cheminFichier')->getData();
        if ($cheminFichier) {
            $originalFilename = pathinfo($cheminFichier->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = $originalFilename . '-' . uniqid() . '.' . $cheminFichier->guessExtension();
            $cheminFichier->move(
                $this->getParameter('musique_files_directory'),
                $filename
            );
            $musique->setCheminFichier($filename);
        }

        // Handle file upload for the photo
        $photo = $form->get('photo')->getData();
        if ($photo) {
            $originalFilename1 = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            $photoFilename = $originalFilename1 . '-' . uniqid() . '.' . $photo->guessExtension();
            $photo->move(
                $this->getParameter('photo_files_directory'),
                $photoFilename
            );
            $musique->setPhoto($photoFilename);
        }

        $em->flush();

        return $this->redirectToRoute('app_Musique_list');
    }

    return $this->render('Musique/form.html.twig', [
        'title' => 'Update Musique',
        'form' => $form->createView(),
    ]);
}
    
    #[Route('/Musique/list', name: 'app_Musique_list')]
    public function listMusique(MusiqueRepository $MusiqueRepository,PlaylistRepository $PlaylistRepository){

        $MusiquesDB= $MusiqueRepository->findAll();
        $PlaylistsDB = $PlaylistRepository->findAll();

        return $this->render('Musique/list.html.twig',[
            'Musiques' => $MusiquesDB,
            'Playlists' => $PlaylistsDB
        ]);
    }

    #[Route('/Musique/remove/{id}', name: 'app_Musique_remove')]
    public function removeMusique($id, MusiqueRepository $MusiqueRepository, EntityManagerInterface $em){
        $Musique= $MusiqueRepository->find($id);
        $em->remove($Musique);
        $em->flush();
        return $this->redirectToRoute('app_Musique_list');
        //return new Response('Musique deleted');
    }

    #[Route('/Musique/add-to-playlist', name: 'app_Musique_add_to_playlist', methods: ['POST'])]
public function addMusiqueToPlaylist(Request $request, EntityManagerInterface $entityManager): Response
{
    $playlistId = $request->request->get('playlist_id');
    $musiqueId = $request->request->get('musique_id');

    $playlist = $entityManager->getRepository(Playlist::class)->find($playlistId);
    $musique = $entityManager->getRepository(Musique::class)->find($musiqueId);

    if (!$playlist || !$musique) {
        throw $this->createNotFoundException("Playlist or Musique not found!");
    }

    $playlist->addMusique($musique); 
    $entityManager->persist($playlist);
    $entityManager->flush();

    return $this->redirectToRoute('app_Musique_list'); 
}

#[Route('/Musique/playlist/{id}', name: 'app_Musique_by_playlist')]
public function showPlaylistMusic($id, PlaylistRepository $playlistRepo, MusiqueRepository $musiqueRepo)
{
    $selectedPlaylist = $playlistRepo->find($id);
    if (!$selectedPlaylist) {
        throw $this->createNotFoundException('Playlist not found');
    }

    $Musiques = $selectedPlaylist->getMusiques(); // Assuming a relation exists

    return $this->render('Musique/list.html.twig', [
        'Musiques' => $Musiques,
        'Playlists' => $playlistRepo->findAll(),
        'selectedPlaylist' => $selectedPlaylist
    ]);
}



}