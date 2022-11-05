<?php

namespace App\Controller\Admin;

use App\Entity\Design;
use App\Entity\Product;
use App\Form\DesignType;
use App\Form\ProductType;
use App\Repository\DesignRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\ImageService;
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
        private ImageService $imageService,
        private UserRepository $userRepository
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

    #[Route('/users', name: 'admin_users')]
    public function userPage(Request $request)
    {
        return $this->render('admin/users.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }

    #[Route('/user/delete/{id}', name: 'admin_user_delete')]
    public function userDelete(Request $request, int $id): Response
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $message = ["warning", "User does not exist"];

        if ($user) {
            if ($user->getCart()) {
                $this->em->remove($user->getCart());
                $this->em->persist($user);
            }

            $this->em->remove($user);
            $this->em->flush();

            $message = ["success", "User has been deleted"];
        }

        $this->addFlash($message[0], $message[1]);
        return $this->redirectToRoute('admin_users');
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
            if (isset($form['imageUpload'])) {
                $image = $this->imageService->checkAndProcessFile($form['imageUpload']->getData(), true);
                $product->setImage($image);
            }
            $lastOrderItem = $this->productRepository->findOneBy([], ['displayOrder' => 'DESC']);

            $displayOrder = 1;
            if ($lastOrderItem) {
                $displayOrder = $lastOrderItem->getDisplayOrder()+1;
            }

            $product->setDisplayOrder($displayOrder);
            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', 'Product ' . $product->getTitle() . ' has been created!' );
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
        /** @var Product $product */
        $product = $this->productRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($form['imageUpload'])) {
                $image = $this->imageService->checkAndProcessFile($form['imageUpload']->getData(), true);
                $product->setImage($image);
            }
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

    // TODO pridek logo upload aswell
    #[Route('/edit-design/{id}', name: 'edit_design')]
    public function editDesign(Request $request, int $id): Response
    {
        /** @var Design $design */
        $design = $this->designRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(DesignType::class, $design);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageObject = $form['imageUpload']->getData();
            if ($imageObject) {
                $image = $this->imageService->checkAndProcessFile($form['imageUpload']->getData(), false);
                $design->setBackgroundImage($image);
            }
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