<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\DesignType;
use App\Form\ProductType;
use App\Repository\DesignRepository;
use App\Repository\ProductRepository;
use App\Service\ProductOrder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private const PAGE_LIMIT = 15;

    public function __construct(
        private EntityManagerInterface $em,
        private DesignRepository $designRepository,
        private ProductRepository $productRepository,
        private ProductOrder $productOrder,
    )
    {
    }

    #[Route('/', name: 'admin_main')]
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'design' => $this->designRepository->findOneBy([])
        ]);
    }

    //TODO 2 - saugoti pasirinkta pre-made order value ir atnaujinti select lista su ja
    // taip pat jei bus custom rodyt tai (gal veliau save custom order?)

    #[Route('/products', name: 'admin_products')]
    public function productPage(Request $request)
    {
        return $this->render('admin/products.html.twig', [
            'query' => $request->query->get('query'),
            'products' => $this->productRepository->findProductsPaginatedWithSearch(self::PAGE_LIMIT, $request->query->get('query'), $request->query->get('page')),
            'design' => $this->designRepository->getDesignProductLimit()
        ]);
    }

    #[Route('/products/order', name: 'product_order')]
    public function productOrder(Request $request)
    {
        $order = $request->get('order');

        $this->productOrder->orderProductsByParameter($order);

        return $this->redirectToRoute('admin_products');
    }

    #[Route('/products/add', name: 'product_add')]
    public function addProduct(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lastOrderItem = $this->productRepository->findOneBy([], ['displayOrder' => 'DESC']);

            $displayOrder = 1;
            if ($lastOrderItem) {
                $displayOrder = $lastOrderItem->getDisplayOrder()+1;
            }

            $product->setDisplayOrder($displayOrder);
            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', 'Saved!');
            return $this->redirectToRoute('admin_products');
        }

        return $this->render("admin/_form.html.twig", [
            'action' => 'Adding a new product',
            'form' => $form->createView()
        ]);
    }

    #[Route('/products/edit/{id}', name: 'product_edit')]
    public function editProduct(Request $request, int $id): Response
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Edited successfully!');
            return $this->redirectToRoute('admin_products');
        }

        return $this->render("admin/_form.html.twig", [
            'action' => 'Editing product - ' . $product->getTitle(),
            'form' => $form->createView()
        ]);
    }

    #[Route('/products/delete/{id}', name: 'product_delete')]
    public function deleteProduct(Request $request, int $id)
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);

        if ($product) {
            $this->em->remove($product);
            $this->em->flush();
            $this->addFlash('success', 'Product has been deleted');
        } else {
            $this->addFlash('warning', 'No such product found');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    #[Route('/edit-design/{id}', name: 'edit_design')]
    public function editDesign(Request $request, int $id): Response
    {
        $design = $this->designRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(DesignType::class, $design);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Saved!');
            return $this->redirectToRoute('admin_main', ['design' => $design]);
        }

        return $this->render("admin/_form.html.twig", [
            'action' => 'Editing design',
            'form' => $form->createView()
        ]);
    }
}