<?php

namespace App\Controller;

use App\Entity\CollectionT;
use App\Form\CollectionTType;
use App\Repository\CollectionTRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TextileRepository;

#[Route('/collections')]
final class CollectionsController extends AbstractController
{
    #[Route(name: 'app_collections_index', methods: ['GET'])]
    public function index(CollectionTRepository $collectionTRepository): Response
    {
        return $this->render('collections/index.html.twig', [
            'collection_ts' => $collectionTRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_collections_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $collectionT = new CollectionT();
        $user=$this->getUser();
        $collectionT->setUserId($user->getId());
        $form = $this->createForm(CollectionTType::class, $collectionT);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($collectionT);
            $entityManager->flush();

            return $this->redirectToRoute('app_collections_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('collections/new.html.twig', [
            'collection_t' => $collectionT,
            'form' => $form,
            'user'=>$user
        ]);
    }

    #[Route('/{id}', name: 'app_collections_show', methods: ['GET'])]
    public function show(CollectionT $collectionT): Response
    {
        return $this->render('collections/show.html.twig', [
            'collection_t' => $collectionT,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_collections_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CollectionT $collectionT, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CollectionTType::class, $collectionT);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_collections_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('collections/edit.html.twig', [
            'collection_t' => $collectionT,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_collections_delete', methods: ['POST'])]
    public function delete(Request $request, CollectionT $collectionT, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collectionT->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($collectionT);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_collections_index', [], Response::HTTP_SEE_OTHER);
    }
}
