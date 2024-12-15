<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Commandes;
use App\Form\ProduitsType;
use App\Entity\Produits;
use App\Form\CategoriesType;
use App\Repository\CommandesRepository;
use App\Repository\ProduitsRepository;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $admin = ["ROLE_ADMIN","ROLE_CLIENT","ROLE_USER"];         
        $client = ["ROLE_CLIENT","ROLE_USER"];
        $user = [];


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/ajouterproduits', name: 'app_admin_ajouter_produits')]
    public function ajouterProduits(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
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
                $image = $form->get('image')->getData();
                if ($image) {
                    $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFileName = $slugger->slug($originalName);
                    $newNameFile = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();

                    try {
                        $image->move(
                            $this->getParameter('image_dir'),
                            $newNameFile
                        );
                    }catch (FileException $exception){}
                    $produit->setImage($newNameFile);
                }
                $produit = $form->getData();
                $entityManager->persist($produit);
                $entityManager->flush();
    
                // Message de succès
                $this->addFlash('success', 'Le produit a été ajouté avec succès.');
    
                // Redirection
                return $this->redirectToRoute('app_admin_ajouter_produits');
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

    #[Route('/admin/ajoutercategories', name: 'app_admin_ajouter_categories')]
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

    #[Route("/admin/commande", name: "app_admin_afficher_commandes")]
    public function getAllCommande(CommandesRepository $commandesRepository): Response
    {
        // Sélectionner uniquement les commandes avec un statut "En attente"
        $commandes = $commandesRepository->createQueryBuilder('c')
            ->leftJoin('c.leStatut', 's')  // Jointure avec la table des statuts
            ->where('s.libelle = :enAttente')  // Filtrer les statuts "En attente"
            ->setParameter('enAttente', 'En attente')
            ->getQuery()
            ->getResult();

        return $this->render('admin/commande.html.twig', [
            'commandes' => $commandes,
        ]);
    }



    #[Route("/admin/commande/valider/{id}", name: "app_admin_commande_valider", methods: ["GET", "POST"])]
    public function validerCommande(
        int $id,
        CommandesRepository $commandesRepository,
        StatutRepository $statutRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Trouver la commande par son ID
        $commande = $commandesRepository->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('Commande introuvable.');
        }

        // Récupérer le statut correspondant à "Terminée"
        $statutTermine = $statutRepository->findOneBy(['libelle' => 'Terminée']);

        if (!$statutTermine) {
            throw $this->createNotFoundException('Le statut "Terminée" est introuvable.');
        }

        // Mettre à jour le statut de la commande
        $commande->setLeStatut($statutTermine);
        $entityManager->flush();

        // Ajouter un message flash de confirmation
        $this->addFlash('success', 'La commande a été validée avec succès.');

        // Rediriger vers la liste des commandes
        return $this->redirectToRoute('app_admin_afficher_commandes');
    }




}
