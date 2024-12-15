<?php

namespace App\Controller;

use App\Utils\Utils;
use App\Entity\Commandes;
use App\Entity\DetailsCommandes;
use App\Entity\Statut;
use App\Repository\ProduitsRepository;
use App\Repository\CommandesRepository;
use App\Repository\StatutRepository;
use App\Repository\DetailsCommandesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    #[Route('api/produits', name: 'app_api_produits')]
    public function getProducts(Request $request,ProduitsRepository $produitsRepository)
    {
        $response =new Utils();
        $produits = $produitsRepository->findAll();
        return $response->GetJsonResponse($request,$produits);
    }
    #[Route('api/detailscommandes', name: 'app_api_details_commandes')]
    public function getDetailsCommandes(Request $request,DetailsCommandesRepository $detailsCommandesRepository)
    {
        $response =new Utils();
        $detailscommandes = $detailsCommandesRepository->findAll();
        return $response->GetJsonResponse($request,$detailscommandes);
    }

    #[Route('api/panier/add', name: 'add_panier', methods: ['POST'])]
    public function addProduitToPanier(
        Request $request,
        ProduitsRepository $produitsRepository,
        CommandesRepository $commandesRepository,
        StatutRepository $statutRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $content = json_decode($request->getContent(), true);
        $produitId = $content['produit_id'] ?? null;
        if (!$produitId) {
            return $this->json(['error' => 'Missing produit_id'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $produit = $produitsRepository->find($produitId);
        if (!$produit) {
            return $this->json(['error' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $commande = $commandesRepository->findOneBy(['leUser' => $user]) ?? new Commandes();
        if (!$commande->getId()) {
            $commande->setLeUser($user);
            $commande->setLeStatut($statutRepository->find(1)); // Statut "En cours"
            $entityManager->persist($commande);
        }

        $commande->ajouterProduit($produit, $entityManager);
        $entityManager->flush();

        return $this->json(['message' => 'Produit ajouté au panier']);
    }

    #[Route('api/panier/decrement', name: 'decrement_panier', methods: ['POST'])]
    public function decrementProduitInPanier(
        Request $request,
        ProduitsRepository $produitsRepository,
        CommandesRepository $commandesRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $content = json_decode($request->getContent(), true);
        $produitId = $content['produit_id'] ?? null;
        if (!$produitId) {
            return $this->json(['error' => 'Missing produit_id'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $produit = $produitsRepository->find($produitId);
        if (!$produit) {
            return $this->json(['error' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $commande = $commandesRepository->findOneBy(['leUser' => $user]);
        if ($commande) {
            $commande->retirerProduit($produit, $entityManager);
            $entityManager->flush();
        }

        return $this->json(['message' => 'Produit décrémenté dans le panier']);
    }

    #[Route('api/panier/clear', name: 'clear_panier', methods: ['POST'])]
    public function clearPanier(
        CommandesRepository $commandesRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $commande = $commandesRepository->findOneBy(['leUser' => $user]);
        if ($commande) {
            foreach ($commande->getLesDetailsCommandes() as $detail) {
                $entityManager->remove($detail);
            }
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->json(['message' => 'Panier supprimé']);
    }
    #[Route('/api/commandes/update/{id}', name: 'app_api_update_commande', methods: ['PUT'])]
public function updateCommande(
    $id,
    CommandesRepository $commandesRepository,
    StatutRepository $statutRepository,
    EntityManagerInterface $entityManager
): JsonResponse {
    // Récupérer la commande à partir de l'ID
    $commande = $commandesRepository->find($id);

    if (!$commande) {
        return $this->json(['error' => 'Commande non trouvée'], JsonResponse::HTTP_NOT_FOUND);
    }

    // Vérifier si l'utilisateur est propriétaire de la commande
    $user = $this->getUser();
    if ($commande->getLeUser() !== $user) {
        return $this->json(['error' => 'Accès non autorisé'], JsonResponse::HTTP_FORBIDDEN);
    }

    // Modifier le statut de la commande
    $statutValide = $statutRepository->findOneBy(['libelle' => 'valide']);
    if (!$statutValide) {
        return $this->json(['error' => 'Statut "valide" introuvable'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    $commande->setLeStatut($statutValide);
    $entityManager->flush();

    // Réinitialiser les détails du panier (les supprimer de la session utilisateur)
    foreach ($commande->getLesDetailsCommandes() as $detail) {
        $entityManager->remove($detail);
    }

    $entityManager->flush();

    return $this->json(['message' => 'Commande validée et panier réinitialisé.']);
}
#[Route('/api/commandes/valider/{id}', name: 'app_api_valider_commande', methods: ['POST'])]
public function validerCommande(
    $id,
    CommandesRepository $commandesRepository,
    StatutRepository $statutRepository,
    EntityManagerInterface $entityManager
): JsonResponse {
    // Récupérer la commande actuelle
    $commande = $commandesRepository->find($id);

    if (!$commande) {
        return $this->json(['error' => 'Commande non trouvée'], JsonResponse::HTTP_NOT_FOUND);
    }

    // Vérifier si l'utilisateur est propriétaire de la commande
    $user = $this->getUser();
    if ($commande->getLeUser() !== $user) {
        return $this->json(['error' => 'Accès non autorisé'], JsonResponse::HTTP_FORBIDDEN);
    }

    // Modifier le statut de la commande actuelle à "valide"
    $statutValide = $statutRepository->findOneBy(['libelle' => 'valide']);
    if (!$statutValide) {
        return $this->json(['error' => 'Statut "valide" introuvable'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
    $commande->setLeStatut($statutValide);

    // Créer une nouvelle commande vide pour l'utilisateur
    $nouvelleCommande = new Commandes();
    $nouvelleCommande->setLeUser($user);

    // Associer un statut initial (par exemple "en cours")
    $statutEnCours = $statutRepository->findOneBy(['libelle' => 'en_cours']);
    if (!$statutEnCours) {
        return $this->json(['error' => 'Statut "en_cours" introuvable'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
    $nouvelleCommande->setLeStatut($statutEnCours);

    // Persister les changements dans la base de données
    $entityManager->persist($nouvelleCommande);
    $entityManager->flush();

    return $this->json([
        'message' => 'Commande validée avec succès',
        'nouvelleCommandeId' => $nouvelleCommande->getId(),
    ]);
}

#[Route('/api/commandes/parcours/{id}', name: 'app_api_parcours_commande', methods: ['GET','POST'])]
function trouverCheminPlusCourt($id, CommandesRepository $commandesRepository): Response {
    $commande = $commandesRepository->find($id);

    if (!$commande) {
        return $this->json(['error' => 'Commande non trouvée'], JsonResponse::HTTP_NOT_FOUND);
    }


    $lesProduits = [];


foreach ($commande->getLesDetailsCommandes() as $detailCommande) {
    $produit = $detailCommande->getLeProduit();
    $lesProduits[] = [
        'id' => $produit->getId(),
        'libelle' => $produit->getLibelle(), 
        'x' => $produit->getLeEmplacement()->getPosX(), 
        'y' => $produit->getLeEmplacement()->getPosY(),
    ];
}


    $depart = ['x' => 0, 'y' => 0];
    $positionActuelle = $depart;
    $chemin = [];
    $produitsRestants = $lesProduits;
    $distanceTotale = 0;

    while (!empty($produitsRestants)) {
        $produitLePlusProche = null;
        $distanceMinimale = PHP_INT_MAX;

        foreach ($produitsRestants as $index => $produit) {
            $distance = $this->calculerDistance($positionActuelle, $produit);
            if ($distance < $distanceMinimale) {
                $distanceMinimale = $distance;
                $produitLePlusProche = $index;
            }
        }

        // Ajouter le produit le plus proche au chemin
        $chemin[] = $produitsRestants[$produitLePlusProche];
        $distanceTotale += $distanceMinimale;
        $positionActuelle = $produitsRestants[$produitLePlusProche];

        // Retirer le produit visité de la liste des produits restants
        unset($produitsRestants[$produitLePlusProche]);
    }

    // Retourner au point de départ
    $distanceTotale += $this->calculerDistance($positionActuelle, $depart);

    return $this->render('/home/cheminCommande.html.twig', ['chemin' => $chemin, 'distanceTotale' => $distanceTotale]);
}

function calculerDistance($point1, $point2) {
    return round(sqrt(pow($point2['x'] - $point1['x'], 2) + pow($point2['y'] - $point1['y'], 2)),2);
}


}