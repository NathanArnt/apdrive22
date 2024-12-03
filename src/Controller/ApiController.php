<?php

namespace App\Controller;

use App\Utils\Utils;
use App\Entity\Commandes;
use App\Entity\DetailsCommandes;
use App\Repository\ProduitsRepository;
use App\Repository\CommandesRepository;
use App\Repository\StatutRepository;
use App\Repository\DetailsCommandesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

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
    #[Route('/api/commandes', name: 'api_create_commande', methods: ['POST'])]
    public function createCommande(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepo,
        StatutRepository $statutRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Vérifier si les détails de la commande sont fournis
        if (!isset($data['details']) || empty($data['details'])) {
            return new JsonResponse(['error' => 'Aucun détail de commande fourni'], 400);
        }

        // Créer une nouvelle commande
        $commande = new Commandes();

        // Associer un utilisateur (remplacez par votre logique utilisateur)
        $user = $this->getUser(); // Utilisateur connecté
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié'], 401);
        }
        $commande->setLeUser($user);

        // Associer un statut initial (par exemple "En cours")
        $statut = $statutRepo->findOneBy(['nom' => 'En cours']); // Adaptez en fonction de vos statuts
        $commande->setLeStatut($statut);

        // Associer les détails de commande
        foreach ($data['details'] as $detailData) {
            $detail = new DetailsCommandes();
            $detail->setLeProduit($detailData['leProduit']['id']);
            $detail->setQuantite($detailData['quantite']);
            $detail->setLaCommande($commande);
            $commande->addLesDetailsCommande($detail);
            $em->persist($detail);
        }

        // Persister la commande
        $em->persist($commande);
        $em->flush();

        return new JsonResponse(['success' => 'Commande créée avec succès'], 201);
    }
}
