<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use App\Repository\CommandesRepository;
use App\Repository\StatutRepository;
use App\Entity\Commandes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ProduitsRepository $produitsRepository,
        CommandesRepository $commandesRepository,
        StatutRepository $statutRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        $commande = null;
        $totalPanier = 0;

        if ($user) {
            // Récupérer ou créer la commande en cours pour l'utilisateur
            $commande = $commandesRepository->findOneBy(['leUser' => $user]);

            if (!$commande) {
                $commande = new Commandes();
                $commande->setLeUser($user);
                $commande->setLeStatut($statutRepository->find(1)); // Statut "en cours"
                $entityManager->persist($commande);
                $entityManager->flush();
            }

            // Calculer le total du panier
            $totalPanier = $commande->calculerTotal();
        }

        // Gestion de l'ajout de produit au panier
        if ($request->isMethod('POST') && $request->request->get('produit_id')) {
            $produitId = $request->request->get('produit_id');
            if ($produitId && $commande) {
                $produit = $produitsRepository->find($produitId);
                if ($produit) {
                    // Ajouter le produit au panier
                    $commande->ajouterProduit($produit, $entityManager);

                    // Synchroniser les modifications
                    $entityManager->flush();

                    // Recalculer le total après ajout
                    $totalPanier = $commande->calculerTotal();

                    // Récupérer une version actualisée de la commande
                    $commande = $commandesRepository->findOneBy(['leUser' => $user]);
                }
            }
        }

        // Gestion de la décrémentation de la quantité d'un produit dans le panier
        if ($request->isMethod('POST') && $request->request->get('decrement-id')) {
            $produitId = $request->request->get('decrement-id');
            if ($produitId && $commande) {
                $produit = $produitsRepository->find($produitId);
                if ($produit) {
                    // Retirer le produit du panier
                    $commande->retirerProduit($produit, $entityManager);

                    // Synchroniser les modifications
                    $entityManager->flush();

                    // Recalculer le total après modification
                    $totalPanier = $commande->calculerTotal();
                }
            }
        }

        // Gestion de la suppression du panier
        if ($request->isMethod('POST') && $request->request->get('supprimer_panier')) {
            if ($commande) {
                // Supprimer les détails de la commande
                foreach ($commande->getLesDetailsCommandes() as $detail) {
                    $entityManager->remove($detail);
                }

                // Supprimer la commande elle-même
                $entityManager->remove($commande);
                $entityManager->flush();

                // Réinitialiser le total après suppression
                $totalPanier = 0;
            }

            // Rediriger vers la page d'accueil après suppression du panier
            return $this->redirectToRoute('app_home');
        }

        // Récupérer tous les produits pour affichage
        $produits = $produitsRepository->findAll();

        // Transmettre les données à Twig
        return $this->render('home/index.html.twig', [
            'produits' => $produits,
            'totalPanier' => $totalPanier,
            'commande' => $commande, // Passer la commande pour affichage des détails
        ]);
    }
}
