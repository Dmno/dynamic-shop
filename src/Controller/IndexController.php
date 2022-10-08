<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\DesignRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private DesignRepository $designRepository,
        private ProductRepository $productRepository,
        private CartRepository $cartRepository
    )
    {
    }

    #[Route('/', name: 'main')]
    public function index(): Response
    {
        $cartProducts = [];
        if ($this->getUser()) {
            /** @var Cart $cart */
            $cart = $this->cartRepository->findOneBy(['userId' => $this->getUser()]);
            if ($cart) {
                $cartProducts = $this->productRepository->findAllById($cart->getProducts());
            }
        }

        $design = $this->designRepository->findOneBy([]);

        return $this->render('main/index.html.twig', [
            'design' => $design,
            'products' => $this->productRepository->getAllProductsWithLimit($design->getProductCount()),
            'cart' => $cartProducts
        ]);
    }
}