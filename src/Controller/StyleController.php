<?php

namespace App\Controller;

use App\Entity\Style;
use App\Form\AddEditStyleType;
use App\Repository\StyleRepository;
use App\Repository\PeintureRepository;
use App\Repository\MusiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class StyleController extends AbstractController
{
    #[Route('/style', name: 'app_style')]
    public function index(): Response
    {
        return $this->render('style/index.html.twig', [
            'controller_name' => 'StyleController',
        ]);
    }

    
   /*  #[Route('/style/new', name: 'app_style_new')]
    public function newStyle(Request $request,EntityManagerInterface $em){
        $style= new Style();
        $form= $this->createForm(AddEditStyleType::class,$style);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($style);
            $em->flush();
            return $this->redirectToRoute('app_style_list');
        }
        return $this->render('style/form.html.twig',[
            'title' => 'Add style',
            'form'=> $form
        ]);
    }    */

  /*   #[Route('/new', name: 'app_style_new')]
    public function newStyle(Request $request, ManagerRegistry $doctrine){
        $style = new Style();
        //$style->setTitle('Abc'); //champs du formulaire pré-rempli
        $em= $doctrine->getManager();
        $form= $this->createForm(AddEditStyleType::class, $style);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->persist($style);
            $em->flush();
            return $this->redirectToRoute('app_style_list');
        }
        return $this->render('style/form.html.twig', [
            'title' => 'Add Style',
            'form' => $form
        ]);

    }
    #[Route('/style/edit/{id}', name: 'app_style_edit')]
    public function editStyle($id, Request $request,EntityManagerInterface $em, StyleRepository $styleRepository){
        $style= $styleRepository->find($id);
        $form= $this->createForm(AddEditStyleType::class,$style);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$em->persist($author);
            $em->flush();
            return $this->redirectToRoute('app_style_list');
        }
        return $this->render('style/form.html.twig',[
            'title' => 'Update Style',
            'form'=> $form
        ]);
    } */


    #[Route('/style/new', name: 'app_style_new')]
    public function newStyle(Request $request, EntityManagerInterface $em): Response
    {
        $style = new Style();
        $form = $this->createForm(AddEditStyleType::class, $style);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('extab')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('app_style_new');
                }

                $style->setExtab($newFilename);
            }

            $em->persist($style);
            $em->flush();

            return $this->redirectToRoute('app_style_list');
        }

        return $this->render('style/form.html.twig', [
            'title' => 'Add Style',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/style/edit/{id}', name: 'app_style_edit')]
    public function editStyle($id, Request $request, EntityManagerInterface $em, StyleRepository $styleRepository): Response
    {
        $style = $styleRepository->find($id);
        $form = $this->createForm(AddEditStyleType::class, $style);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('extab')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('app_style_edit', ['id' => $style->getId()]);
                }

                // Supprimer l'ancienne image si elle existe
                if ($style->getExtab()) {
                    $oldFilePath = $this->getParameter('uploads_directory') . '/' . $style->getExtab();
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $style->setExtab($newFilename);
            }

            $em->flush();

            return $this->redirectToRoute('app_style_list');
        }

        return $this->render('style/form.html.twig', [
            'title' => 'Update Style',
            'form' => $form->createView(),
            'style' => $style,
        ]);
    }

    #[Route('/style/remove/{id}', name: 'app_style_remove')]
    public function removeStyle($id, StyleRepository $styleRepository, EntityManagerInterface $em){
        $style= $styleRepository->find($id);
        $em->remove($style);
        $em->flush();
        return $this->redirectToRoute('app_style_list');
        //return new Response('Author deleted');
    }

    #[Route('/style/list', name: 'app_style_list')]
    public function listStyle(StyleRepository $styleRepository,PeintureRepository $peintureRepository,MusiqueRepository $musiqueRepository){
        $stylesDB= $styleRepository->findAll();
        $musiquesDB = $musiqueRepository->findAll();
        $peinturesDB = $peintureRepository->findAll();
        return $this->render('style/list.html.twig',[
            'styles' => $stylesDB,
            'peintures' => $peinturesDB,
            'Musiques' => $musiquesDB
        ]);
    }

    #[Route('/style/details/{id}', name: 'app_style_details')]
    public function styleDetails($id, StyleRepository $styleRepository){
        $styleDB= $styleRepository->find($id);
        //$author= $this->authors[$id - 1];
        return $this->render('style/details.html.twig',[
            'style' => $styleDB
        ]);
    }

}
