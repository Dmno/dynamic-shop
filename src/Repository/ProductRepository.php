<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Get all products with image join
     */
    public function getProductsWithJoins($qb = null)
    {
        $qb = $qb ?? $this->createQueryBuilder('p');

        return $qb
            ->leftJoin('p.image', 'i')
            ->select('p.id, p.title, p.description, p.regularPrice, p.memberPrice, p.displayOrder, i.title as image');
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findAllById(array $productIds): array
    {
        $products = $this->getProductsWithJoins()
            ->andWhere('p.id in (:productIds)')
            ->setParameter('productIds', $productIds)
            ->getQuery()
            ->getResult()
        ;

        $productCount = array_count_values($productIds);

        foreach ($products as $product) {
            $product->count = $productCount[$product->getId()];
        }

        return $products;
    }

    /**
     * For the main page
     * @param int $limit
     * @return array
     */
    public function getProductsWithLimitAndOrder(int $limit): array
    {
        return $this->getProductsWithJoins()
            ->setMaxResults($limit)
            ->addOrderBy('p.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * For the admin page
     * @return array
     */
    public function getAllProductsWithOrder(): array
    {
        return $this->createQueryBuilder('p')
            ->addOrderBy('p.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getAllProductsWithCustomOrder(array $order): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy($order[0], $order[1])
            ->getQuery()
            ->getResult();
    }

    public function findProductsPaginatedWithSearch(int $pageLimit, ?string $query, ?int $page): PaginationInterface
    {
        $pageNumber = $page ?? 1;

        $qb = $this->getProductsWithJoins();

        if (isset($query)) {
            $qb
                ->andWhere('p.title LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        $qb->addOrderBy('p.displayOrder', 'ASC');

        return $this->paginator->paginate($qb->getQuery(), $pageNumber, $pageLimit);
    }
}