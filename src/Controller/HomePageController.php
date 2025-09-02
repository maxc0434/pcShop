<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(CategorieRepository $categorieRepo): Response
    {
        return $this->render('home_page/index.html.twig', [
                'categories'=>$categorieRepo->findAll()
        ]);
    }

}
