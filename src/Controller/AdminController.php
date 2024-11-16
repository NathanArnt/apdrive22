<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\ProduitsType;
use App\Entity\Produits;
use App\Form\CategoriesType;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin')]
    public function index(): Response
    {
        $admin = ["ROLE_ADMIN","ROLE_CLIENT","ROLE_USER"];         
        $client = ["ROLE_CLIENT","ROLE_USER"];
        $user = [];


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/dashboard/ajouterproduits', name: 'app_admin_dashboard_ajouter_produits')]
    public function ajouterProduits(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'une nouvelle instance de l'entité Produit
        $produit = new Produits();
    
        // Création du formulaire en associant l'entité Produit
        $form = $this->createForm(ProduitsType::class, $produit);

        // Traitement de la requête HTTP
        $form->handleRequest($request);

        // Vérification si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Sauvegarde du produit
                $produit = $form->getData();
                $entityManager->persist($produit);
                $entityManager->flush();
    
                // Message de succès
                $this->addFlash('success', 'Le produit a été ajouté avec succès.');
    
                // Redirection
                return $this->redirectToRoute('app_admin_dashboard_ajouter_produits');
            } else {
                // Message flash si le formulaire est soumis mais invalide
                $this->addFlash('error', 'Le formulaire contient des erreurs. Veuillez les corriger.');
            }
        }
    

        // Affichage du formulaire dans la vue Twig
        return $this->render('admin/ajouterProduits.html.twig', [
            'produitsForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/dashboard/ajoutercategories', name: 'app_admin_dashboard_ajouter_categories')]
    public function ajouterCategorie(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'une nouvelle instance de l'entité Produit
        $categorie = new Categories();
    
        // Création du formulaire en associant l'entité Produit
        $form = $this->createForm(CategoriesType::class, $categorie);

        // Traitement de la requête HTTP
        $form->handleRequest($request);

        // Vérification si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Sauvegarde du produit
                $categorie = $form->getData();
                $entityManager->persist($categorie);
                $entityManager->flush();
    
                // Message de succès
                $this->addFlash('success', 'Le produit a été ajouté avec succès.');
    
                // Redirection
                return $this->redirectToRoute('app_admin_dashboard_ajouter_categories');
            } else {
                // Message flash si le formulaire est soumis mais invalide
                $this->addFlash('error', 'Le formulaire contient des erreurs. Veuillez les corriger.');
            }
        }
    

        // Affichage du formulaire dans la vue Twig
        return $this->render('admin/categories.html.twig', [
            'categoriesForm' => $form->createView(),
        ]);
    }
    #[Route('/admin/dashboard/voirproduits', name: 'app_admin_voir_produits')]
    public function voirProduits(ProduitsRepository $produitsRepository): Response
    {

        return $this->render('admin/voirProduits.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
