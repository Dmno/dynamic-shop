<?php

namespace App\Controller;

use App\Repository\DesignRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private DesignRepository $designRepository,
        private ProductRepository $productRepository
    )
    {
    }

    #[Route('/', name: 'main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'design' => $this->designRepository->findOneBy([]),
            'products' => $this->productRepository->findAll()
        ]);
    }
}