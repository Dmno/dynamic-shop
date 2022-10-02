<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em
    )
    {
    }

    #[Route('/add', name: 'cart_add')]
    public function addToCart(Request $request)
    {
        $userId = $request->request->get('userId');
        $productId = $request->request->get('productId');
        // TODO pataisyk user_id_id in entity
        if ($userId) {
            $user = $this->userRepository->findOneBy(['id' => $userId]);
            $cart = $user->getCart();

            $products = [];
            if ($cart instanceof Cart) {
                $products = $cart->getProducts();
            } else {
                $cart = new Cart();
                $cart->setUserId($user);
            }

            $products[] = $productId;

            $cart->setProducts($products);
            $this->em->persist($cart);
            $this->em->flush();
        } else {
            if ($request->isXMLHttpRequest()) {
                return new JsonResponse(['user' => false]);
            }
        }

        if ($request->isXMLHttpRequest()) {
            return new JsonResponse(['user' => true]);
        }
        return true;
    }

    #[Route('/remove', name: 'cart_remove')]
    public function removeFromCart(Request $request)
    {
        $userId = $request->request->get('userId');
        $productId = $request->request->get('productId');

        if ($userId) {
            $user = $this->userRepository->findOneBy(['id' => $userId]);

            $cart = $user->getCart();
            $products = $cart->getProducts();

            $position = array_search($productId, $products, true);

            if ($position !== false) {
                unset($products[$position]);

                $cart->setProducts($products);
                $this->em->persist($cart);
                $this->em->flush();
            }

        } else if ($request->isXMLHttpRequest()) {
            return new JsonResponse(['user' => false]);
        }

        if ($request->isXMLHttpRequest()) {
            return new JsonResponse(['user' => true]);
        }
        return true;
    }
}