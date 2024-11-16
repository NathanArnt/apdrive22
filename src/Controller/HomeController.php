<?php

// src/Controller/HomeController.php

namespace App\Controller;

use App\Repository\CommandesRepository;
use App\Repository\DetailsCommandesRepository;
use App\Repository\ProduitsRepository;
use App\Repository\StatutRepository;
use App\Entity\Commandes;
use App\Entity\DetailsCommandes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(
        ProduitsRepository $produitsRepository,
        CommandesRepository $commandesRepository,
        DetailsCommandesRepository $detailsCommandesRepository,
        StatutRepository $statutRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        
        // Vérifiez que l'utilisateur est connecté
        if (!$user) {
            return $this->redirectToRoute('app_login'); // Rediriger si l'utilisateur n'est pas connecté
        }

        // Récupérer les produits
        $produits = $produitsRepository->findAll();
        
        // Vérifiez que les produits existent
        if (!$produits) {
            throw $this->createNotFoundException('Aucun produit trouvé');
        }

        // Récupérer les statuts "active" et "inactive"
        $statutActive = $statutRepository->findOneBy(['libelle' => 'active']);
        $statutInactive = $statutRepository->findOneBy(['libelle' => 'inactive']);

        // Vérifier si la commande en cours existe déjà
        $commandeEnCours = $commandesRepository->findOneBy([
            'leUser' => $user,
            'leStatut' => $statutActive // Recherche de la commande avec statut "active"
        ]);

        // Si aucune commande en cours n'est trouvée, en créer une nouvelle
        if (!$commandeEnCours) {
            $commandeEnCours = new Commandes();
            $commandeEnCours->setLeUser($user);
            $commandeEnCours->setLeStatut($statutActive); // Attribuer le statut "active"
            $entityManager->persist($commandeEnCours);
            $entityManager->flush();
        }

        // Si un produit est envoyé via le formulaire, l'ajouter à la commande
        if ($request->isMethod('POST')) {
            $produitId = $request->get('produit_id');
            $produit = $produitsRepository->find($produitId);

            if ($produit) {
                // Ajouter le produit à la commande (assurez-vous que la méthode est correcte)
                $detailCommande = $commandeEnCours->getLeDetailCommande();

                // Si le détail de la commande n'existe pas, en créer un nouveau
                if (!$detailCommande) {
                    $detailCommande = new DetailsCommandes();
                    $commandeEnCours->setLeDetailCommande($detailCommande);
                    $entityManager->persist($detailCommande);
                }

                // Ajouter le produit dans le détail de la commande
                $detailCommande->ajouterProduit($produit);  // Assurez-vous que la méthode 'ajouterProduit' existe
                $entityManager->flush();  // Enregistrer les changements
            }
        }

        // Calculer le total du panier
        $totalPanier = 0;
        if ($commandeEnCours && $commandeEnCours->getLeDetailCommande()) {
            $totalPanier = $commandeEnCours->getLeDetailCommande()->calculerTotalPanier();
        }

        // Passer la commande et les produits au template
        return $this->render('home/index.html.twig', [
            'produits' => $produits,
            'totalPanier' => $totalPanier,
            'commandeEnCours' => $commandeEnCours,  // Passer les produits du panier
        ]);
    }
}
