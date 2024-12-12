<?php

namespace App\Controller;

use App\Form\ProduitsType;
use App\Entity\Produits;
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
    #[Route('/admin/produits', name: 'app_admin_produits')]
    public function getProduits(): Response
    {

        return $this->render('admin/produits.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/commandes', name: 'app_admin_commandes')]
    public function getCommandes(): Response
    {

        return $this->render('admin/commandes.html.twig', [
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
}
