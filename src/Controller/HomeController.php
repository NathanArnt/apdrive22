<?php

namespace App\Controller;

use App\Repository\CommandesRepository;
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

        // Récupérer le statut "active"
        $statutActive = $statutRepository->findOneBy(['libelle' => 'active']);

        // Vérifier si la commande en cours existe déjà
        $commandeEnCours = $commandesRepository->findOneBy([
            'leUser' => $user,
            'leStatut' => $statutActive
        ]);

        // Si aucune commande en cours n'est trouvée, en créer une nouvelle
        if (!$commandeEnCours) {
            $commandeEnCours = new Commandes();
            $commandeEnCours->setLeUser($user);
            $commandeEnCours->setLeStatut($statutActive);

            // Créer également un détail de commande pour cette commande
            $detailCommande = new DetailsCommandes();
            $detailCommande->setLaCommande($commandeEnCours);

            $entityManager->persist($commandeEnCours);
            $entityManager->persist($detailCommande);
            $entityManager->flush();
        } else {
            // Récupérer le détail de commande existant
            $detailCommande = $commandeEnCours->getLeDetailCommande();
        }

        // Si un produit est envoyé via le formulaire, l'ajouter à la commande
        if ($request->isMethod('POST')) {
            $produitId = $request->get('produit_id');
            $produit = $produitsRepository->find($produitId);

            if ($produit) {
                // Ajouter ou mettre à jour la quantité du produit dans le détail de commande
                $detailCommande->ajouterProduit($produit);

                // Sauvegarder les changements
                $entityManager->flush();
            }
        }

        // Calculer le total du panier
        $totalPanier = $detailCommande ? $detailCommande->calculerTotalPanier() : 0;

        // Passer la commande et les produits au template
        return $this->render('home/index.html.twig', [
            'produits' => $produits,
            'totalPanier' => $totalPanier,
            'commandeEnCours' => $commandeEnCours,
        ]);
    }
}
