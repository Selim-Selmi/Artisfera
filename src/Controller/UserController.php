<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Musique;
use App\Entity\Peinture;
use App\Entity\Playlist;
use App\Entity\Style;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddUserType;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\ModifierProfileType;
use App\Form\ModifierProfileAdminType;
use App\Repository\MusiqueRepository;
use App\Repository\PeintureRepository;
use App\Repository\StyleRepository;
use App\Form\AddEditPeintureType;



class UserController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $user = $this->getUser();       
        return $this->render('user/dashboard.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user
        ]);
    }    
    #[Route('/dash', name: 'app_dash')]
    public function dash(): Response
    {
        $user = $this->getUser();       
        return $this->render('Back.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user
        ]);
    }
    #[Route('/profile/admin', name: 'app_profile_admin')]
    public function profileAdmine(UserRepository $userRepository,Request $request){
        $us = $this->getUser();
        return $this->render('user/profileAdmine.html.twig',[
            'user' => $us
         ]);
    }
    #[Route('/membre', name: 'app_membre')]
    public function home(UserRepository $userRepository,Request $request,PeintureRepository $peintureRepository,MusiqueRepository $musiqueRepository): Response
    {
        $peinturesDB = $peintureRepository->findAll();
        $musiquesDB = $musiqueRepository->findAll();
            return $this->render('user/frontMembre.html.twig',[
                'peintures' => $peinturesDB,
                'Musiques' => $musiquesDB,]);

    }    
    
    
    #[Route('/login', name: 'app_forgot_password')]
    public function forgotpassword(): Response
    {
        return $this->render('security/login.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/list', name: 'app_user_list')]
    public function listuser(UserRepository $userRepository){
        $userBD = $userRepository->findAll();
        $user=$this->getUser();
        return $this->render('user/list.html.twig',[
            'users' => $userBD,
            'user'=>$user
        ]);
    }
    #[Route('/ajouter', name: 'app_user_new')]
    public function AjouterUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $us=$this->getUser();
        $form = $this->createForm(AddUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form,
            'user'=>$us
            
        ]);
    }
    #[Route('/profile', name: 'app_profile')]
    public function profile(UserRepository $userRepository,Request $request){
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        return $this->render('user/profile.html.twig',[
            'user' => $user
         ]);
    }
    
    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function edituser($id, Request $request,EntityManagerInterface $em, UserRepository $userRepository){
        $user= $userRepository->find($id);
        $us=$this->getUser();
        $form= $this->createForm(EditUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_user_list');
        }
        return $this->render('user/formEditUser.html.twig',[
            'title' => 'Update user',
            'form'=> $form,
            'user'=>$us
        ]);
    }
    #[Route('/user/remove/{id}', name: 'app_user_remove')]
public function removeuser($id, UserRepository $userRepository, EntityManagerInterface $em, Request $request, TokenStorageInterface $tokenStorage)
{
    $user = $userRepository->find($id);
    if ($user === $this->getUser()) {
        $tokenStorage->setToken(null);  
        $request->getSession()->invalidate();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_login');
    }
    $em->remove($user);
    $em->flush();
    return $this->redirectToRoute('app_user_list');
}
    
    #[Route('/change-password', name: 'app_change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();


        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('new_password');
            if (!empty($newPassword)) {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe modifié avec succès.');
                return $this->redirectToRoute('app_profile');
            }
        }

        return $this->render('user/change_password.html.twig');
    }

       #[Route('/profile/delete', name: 'app_delete_profile', methods: ['POST'])]
    public function deleteProfile(Request $request,EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();

        if ($user) {
            // Supprimer l'utilisateur de la base de données
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            // Déconnexion de l'utilisateur après la suppression
            $this->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();

            // Rediriger vers la route de déconnexion
            return $this->redirectToRoute('app_logout');
        }

        // Si l'utilisateur n'existe pas ou une erreur, retourner au profil
        return $this->redirectToRoute('app_profile');
    }
    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function editProfile(Request $request, EntityManagerInterface $em)
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Créer le formulaire avec les données de l'utilisateur
        $form = $this->createForm(ModifierProfileType::class, $user);

        // Traiter la requête du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('app_profile');
        }
        return $this->render('user/edit_profile.html.twig', [
            'form' => $form->createView(),
            'user' =>$user
        ]);
    }
    #[Route('/profileadmin/edit', name: 'app_profile_admin_edit')]
    public function editProfileAdmin(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $form = $this->createForm(ModifierProfileAdminType::class, $user);
        $form->handleRequest($request);
        $us=$this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('app_profile_admin');
        }
        return $this->render('user/edit_profile_admin.html.twig', [
            'form' => $form->createView(),
            'user'=>$us

        ]);
    }
    #[Route('/search', name: 'user_search', methods: ['POST'])]
        public function search(Request $request, UserRepository $userRepository): Response
        {
            $query = $request->request->get('query'); // Récupérer la valeur du champ de recherche
            
            $users = $userRepository->searchByKeyword($query);

            return $this->render('user/list.html.twig', [
                'users' => $users,
            ]);
        }
        #[Route('/Afficherpa', name: 'app_peinture_afficher')]
        public function listPeinture(PeintureRepository $peintureRepository,MusiqueRepository $musiqueRepository)
        {
            $musiquesDB = $musiqueRepository->findAll();
            $peinturesDB = $peintureRepository->findAll();
            return $this->render('user/ListePeinture.html.twig', [
                'peintures' => $peinturesDB,
                'Musiques' => $musiquesDB
            ]);
        }
        #[Route('/style/afficher', name: 'app_style_afficher')]
        public function listStyle(StyleRepository $styleRepository,PeintureRepository $peintureRepository,MusiqueRepository $musiqueRepository){
            $stylesDB= $styleRepository->findAll();
            $musiquesDB = $musiqueRepository->findAll();
            $peinturesDB = $peintureRepository->findAll();
            return $this->render('user/AfficherStile.html.twig',[
                'styles' => $stylesDB,
                'peintures' => $peinturesDB,
                'Musiques' => $musiquesDB
            ]);
        }
        #[Route('/style/details/afficher/{id}', name: 'app_style_details_base')]
        public function styleDetails($id, StyleRepository $styleRepository){
            $styleDB= $styleRepository->find($id);
            //$author= $this->authors[$id - 1];
            return $this->render('user/details_style_afficher.html.twig',[
                'style' => $styleDB
            ]);
        }
        #[Route('/list/back/peinture', name: 'app_peinture_list_back')]
        public function peinture(PeintureRepository $peintureRepository,StyleRepository $styleRepository){
            $peintureBD = $peintureRepository->findAll();
            $us=$this->getUser();
            return $this->render('user/BackPeinture.html.twig',[
                'peintures' => $peintureBD ,
                'user'=>$us
            ]);
        }
        #[Route('/details/peinture/{id}', name: 'app_peinture_details_base')]
        public function peintureDetailsbase($id, PeintureRepository $peintureRepository)
        {
            $peintureDB = $peintureRepository->find($id);
            return $this->render('user/details_peinture_afficher.html.twig', [
                'peinture' => $peintureDB
            ]);
        }
        #[Route('/edit/peinture/{id}', name: 'app_peinture_edit_back')]
        public function editPeinture($id, Request $request, EntityManagerInterface $em, PeintureRepository $peintureRepository)
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
    
            return $this->render('user/editPeintureBack.html.twig', [
                'title' => 'Modifier une Peinture',
                'form' => $form->createView(),
                'user'=>$us

            ]);
        }

}
