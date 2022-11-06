<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Design;
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
        $cartProducts = $this->getCartProducts();
        $cartTotals = [];
        if ($cartProducts) {
            $cartTotals = $this->getCartTotal($cartProducts);
        }

        $design = $this->getDesign();

        return $this->render('main/index.html.twig', [
            'design' => $design,
            'products' => $this->productRepository->getProductsWithLimitAndOrder($design['productCount']),
            'cart' => $cartProducts,
            'cartTotal' => $cartTotals['price']
        ]);
    }

    #[Route('/checkout', name: 'checkout_main')]
    public function checkoutMain(): Response
    {
        $cartProducts = $this->getCartProducts();
        $cartTotals = [];
        if ($cartProducts) {
            $cartTotals = $this->getCartTotal($cartProducts);
        }

        return $this->render('main/checkout.html.twig', [
            'design' => $this->getDesign(),
            'cart' => $cartProducts,
            'cartTotal' => $cartTotals['price'],
            'cartTotalItems' => $cartTotals['count']
        ]);
    }

    private function getCartProducts(): array
    {
        $cartProducts = [];

        if ($this->getUser()) {
            /** @var Cart $cart */
            $cart = $this->cartRepository->findOneBy(['userId' => $this->getUser()]);
            if ($cart) {
                $cartProducts = $this->productRepository->findAllById($cart->getProducts());
            }
        }

        return $cartProducts;
    }

    private function getCartTotal(array $cartProducts): array
    {
        $cartTotalPrice = 0;
        $cartTotalCount = 0;
        foreach ($cartProducts as $cartProduct) {
            $cartTotalPrice += $cartProduct['memberPriceTotal'];
            $cartTotalCount += $cartProduct['count'];
        }
        return [
            'price' => $cartTotalPrice,
            'count' => $cartTotalCount
        ];
    }

    private function getDesign(): array
    {
        /** @var Design $design */
        return $this->designRepository->getDesignWithJoins();
    }
}
