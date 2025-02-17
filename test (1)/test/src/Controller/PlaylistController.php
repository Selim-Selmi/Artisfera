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
        $user=$this->getUser();
        $Playlist= new Playlist();
        $Playlist->setUserId($user->getId());
        $form= $this->createForm(AddEditPlaylistType::class,$Playlist);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Playlist);
            $em->flush();
            return $this->redirectToRoute('app_Musique_list');
        }
        return $this->render('Playlist/form.html.twig',[
            'title' => 'Add Playlist',
            'form'=> $form,
            'user'=> $user
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
            return $this->redirectToRoute('app_Musique_list');
        }
        return $this->render('Playlist/form.html.twig',[
            'title' => 'Update Playlist',
            'form'=> $form
        ]);
    }

    /* #[Route('/Playlist/list', name: 'app_Playlist_list')]
    public function listPlaylist(PlaylistRepository $PlaylistRepository){
        $PlaylistsDB= $PlaylistRepository->findAll();
        return $this->render('Playlist/list.html.twig',[
            'Playlists' => $PlaylistsDB
        ]);
    } */

    #[Route('/Playlist/remove/{id}', name: 'app_Playlist_remove')]
    public function removePlaylist($id, PlaylistRepository $PlaylistRepository, EntityManagerInterface $em){
        $Playlist= $PlaylistRepository->find($id);
        $em->remove($Playlist);
        $em->flush();
        return $this->redirectToRoute('app_Musique_list');
        //return new Response('Playlist deleted');
    }

    #[Route('/playlist/{id}', name: 'playlist_show')]
public function show(Playlist $playlist): Response
{
    return $this->render('playlist/show.html.twig', [
        'playlist' => $playlist
    ]);
}

#[Route('/remove/Playlist/{id}', name: 'app_Playlist_remove_back')]
public function removePlaylistt($id, PlaylistRepository $PlaylistRepository, EntityManagerInterface $em){
    $Playlist= $PlaylistRepository->find($id);
    $em->remove($Playlist);
    $em->flush();
    return $this->redirectToRoute('app_Playlist_list_back');
    //return new Response('Playlist deleted');
}

#[Route('/Playlist/list', name: 'app_Playlist_list_back')]
public function listPlaylistt(PlaylistRepository $PlaylistRepository){
    $PlaylistsDB= $PlaylistRepository->findAll();
    $user=$this->getUser();
    return $this->render('Playlist/BackPlaylist.html.twig',[
        'playlists' => $PlaylistsDB,
        'user'=> $user,
    ]);
}

#[Route('/edit/Playlist/{id}', name: 'app_Playlist_edit_back')]
    public function editPlaylistt($id, Request $request,EntityManagerInterface $em, PlaylistRepository $PlaylistRepository){
        $Playlist= $PlaylistRepository->find($id);
        $form= $this->createForm(AddEditPlaylistType::class,$Playlist);
        $form->handleRequest($request);
        $user=$this->getUser();
        if($form->isSubmitted() && $form->isValid()){
            //$em->persist($Playlist);
            $em->flush();
            return $this->redirectToRoute('app_Playlist_list_back');
        }
        return $this->render('Playlist/editPlaylistBack.html.twig',[
            'title' => 'Update Playlist',
            'form'=> $form,
            'user'=> $user,
        ]);
    }

    #[Route('/add/Playlist', name: 'app_playlist_new_back')]
    public function newPlaylistt(Request $request,EntityManagerInterface $em){
        $user=$this->getUser();
        $Playlist= new Playlist();
        $Playlist->setUserId($user->getId());
        $form= $this->createForm(AddEditPlaylistType::class,$Playlist);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Playlist);
            $em->flush();
            return $this->redirectToRoute('app_Playlist_list_back');
        }
        return $this->render('Playlist/editPlaylistBack.html.twig',[
            'title' => 'Add Playlist',
            'form'=> $form,
            'user'=> $user
        ]);
    }

}
