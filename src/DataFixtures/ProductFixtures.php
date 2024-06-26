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
                'regularPrice' => 2.99,
                'memberPrice' => 1.99,
                'displayOrder' => 1
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 2
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 3
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 4
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 5
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 6
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 7
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 8
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 9
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 10
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 11
            ],
            [
                'title' => 'Orange',
                'description' => 'A nice orange color fruit',
                'regularPrice' => 1.99,
                'memberPrice' => 0.69,
                'displayOrder' => 12
            ],
        ];

        foreach ($items as $item) {
            $product = new Product();
            $product->setTitle($item['title']);
            $product->setDescription($item['description']);
            $product->setRegularPrice($item['regularPrice']);
            $product->setMemberPrice($item['memberPrice']);
            $product->setDisplayOrder($item['displayOrder']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}