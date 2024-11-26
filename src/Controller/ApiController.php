<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use App\Utils\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
    #[Route('/api/produits', name: 'app_api_produits')]
    public function getProducts(Request $request,ProduitsRepository $produitsRepository)
    {
        $response =new Utils();
        $produits = $produitsRepository->findAll();
        return $response->GetJsonResponse($request,$produits);
    }
}
