<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $items = [
            [
                'title' => 'Banana',
                'description' => 'A very tasty banana',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Banana-Single.jpg/2324px-Banana-Single.jpg',
                'regularPrice' => 2.99,
                'memberPrice' => 1.99
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'image' => 'https://www.e-fresco.io/pub/media/catalog/product/o/r/orange_4.jpg',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69
            ],
        ];

        foreach ($items as $item) {
            $product = new Product();
            $product->setTitle($item['title']);
            $product->setDescription($item['description']);
            $product->setImage($item['image']);
            $product->setRegularPrice($item['regularPrice']);
            $product->setMemberPrice($item['memberPrice']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}