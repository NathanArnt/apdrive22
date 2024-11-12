<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        
// ;       $admin = ["ROLE_ADMIN","ROLE_CLIENT","ROLE_USER"];
//         $client = ["ROLE_CLIENT","ROLE_USER"];
//         $user = [];

        // // $user = $userRepository->find(id:4);
        // // $user->setRoles($client);
        // // $entityManager->flush();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
        // return $this->render('base.html.twig');
    }
    
}
