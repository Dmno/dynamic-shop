<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductOrder
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $em
    )
    {
    }

    public function orderProductsByParameter(string $parameter): void
    {
        $order = $this->getOrderValue($parameter);
        $products = $this->productRepository->getAllProductsWithCustomOrder($order);
        $this->orderAndUpdateProducts($products);
    }

    public function orderAndUpdateProducts(array $products): void
    {
        $i=1;
        /** @var Product $product */
        foreach ($products as $product) {
            $product->setDisplayOrder($i);
            $this->em->persist($product);
            $i++;
        }
        $this->em->flush();
    }

    public function getOrderValue(string $value): array
    {
        return match ($value) {
            "id_desc" => ['p.id', 'DESC'],
            "rprice_asc" => ['p.regularPrice', 'ASC'],
            "rprice_desc" => ['p.regularPrice', 'DESC'],
            "mprice_asc" => ['p.memberPrice', 'ASC'],
            "mprice_desc" => ['p.memberPrice', 'DESC'],
            default => ['p.id', 'ASC']
        };
    }
}