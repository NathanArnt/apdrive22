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
    #[Route('/', name: 'app_home')]
    public function index(){
       
        return $this->render('home/index.html.twig', [
        ]);
    }
    #[Route('/panier', name: 'app_panier')]
    public function validerPanier(){
       
        return $this->render('home/panier.html.twig', [
        ]);
    }
}
