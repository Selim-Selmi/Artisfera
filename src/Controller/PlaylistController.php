<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Playlist;
use App\Form\AddEditPlaylistType;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class PlaylistController extends AbstractController
{
    #[Route('/playlist', name: 'app_playlist')]
    public function index(): Response
    {
        return $this->render('playlist/index.html.twig', [
            'controller_name' => 'PlaylistController',
        ]);
    }

    #[Route('/Playlist/add', name: 'app_Playlist_new')]
    public function newPlaylist(Request $request,EntityManagerInterface $em){
        $Playlist= new Playlist();
        $form= $this->createForm(AddEditPlaylistType::class,$Playlist);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Playlist);
            $em->flush();
            //return $this->redirectToRoute('app_Playlist_list');
        }
        return $this->render('Playlist/form.html.twig',[
            'title' => 'Add Playlist',
            'form'=> $form
        ]);
    }

    #[Route('/Playlist/edit/{id}', name: 'app_Playlist_edit')]
    public function editPlaylist($id, Request $request,EntityManagerInterface $em, PlaylistRepository $PlaylistRepository){
        $Playlist= $PlaylistRepository->find($id);
        $form= $this->createForm(AddEditPlaylistType::class,$Playlist);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$em->persist($Playlist);
            $em->flush();
           // return $this->redirectToRoute('app_Playlist_list');
        }
        return $this->render('Playlist/form.html.twig',[
            'title' => 'Update Playlist',
            'form'=> $form
        ]);
    }

    #[Route('/Playlist/list', name: 'app_Playlist_list')]
    public function listPlaylist(PlaylistRepository $PlaylistRepository){
        $PlaylistsDB= $PlaylistRepository->findAll();
        return $this->render('Playlist/list.html.twig',[
            'Playlists' => $PlaylistsDB
        ]);
    }

    #[Route('/Playlist/remove/{id}', name: 'app_Playlist_remove')]
    public function removePlaylist($id, PlaylistRepository $PlaylistRepository, EntityManagerInterface $em){
        $Playlist= $PlaylistRepository->find($id);
        $em->remove($Playlist);
        $em->flush();
        return $this->redirectToRoute('app_Playlist_list');
        //return new Response('Playlist deleted');
    }

    #[Route('/playlist/{id}', name: 'playlist_show')]
public function show(Playlist $playlist): Response
{
    return $this->render('playlist/show.html.twig', [
        'playlist' => $playlist
    ]);
}

}
