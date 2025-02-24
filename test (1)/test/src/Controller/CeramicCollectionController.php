<?php

namespace App\Controller;

use App\Entity\CeramicCollection;
use App\Form\CeramicCollectionType;
use App\Repository\CeramicCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ceramic/collection')]
final class CeramicCollectionController extends AbstractController
{
    #[Route(name: 'app_ceramic_collection_index', methods: ['GET'])]
    public function index(CeramicCollectionRepository $ceramicCollectionRepository): Response
    {
        return $this->render('ceramic_collection/index.html.twig', [
            'ceramic_collections' => $ceramicCollectionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ceramic_collection_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ceramicCollection = new CeramicCollection();
        $user=$this->getUser();
        $ceramicCollection->setUserId($user->getId()); 
        $form = $this->createForm(CeramicCollectionType::class, $ceramicCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ceramicCollection);
            $entityManager->flush();

            return $this->redirectToRoute('app_ceramic_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ceramic_collection/new.html.twig', [
            'ceramic_collection' => $ceramicCollection,
            'form' => $form,
            'user'=>$user

        ]);
    }

    #[Route('list/{id}', name: 'app_ceramic_collection_show', methods: ['GET'])]
    public function show(CeramicCollection $ceramicCollection): Response
    {
        // Récupérer les œuvres associées à la collection
        $oeuvres = $ceramicCollection->getOeuvres();
        $user=$this->getUser();
        return $this->render('ceramic_collection/show.html.twig', [
            'ceramic_collection' => $ceramicCollection,
            'oeuvres' => $oeuvres,  // Passer les œuvres à la vue
            'user' => $user

        ]);
    }
    

    #[Route('/{id}/edit', name: 'app_ceramic_collection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CeramicCollection $ceramicCollection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CeramicCollectionType::class, $ceramicCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ceramic_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ceramic_collection/edit.html.twig', [
            'ceramic_collection' => $ceramicCollection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ceramic_collection_delete', methods: ['POST'])]
    public function delete(Request $request, CeramicCollection $ceramicCollection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ceramicCollection->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ceramicCollection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ceramic_collection_index', [], Response::HTTP_SEE_OTHER);
    }
}
