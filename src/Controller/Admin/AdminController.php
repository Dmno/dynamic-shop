<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\DesignType;
use App\Repository\DesignRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private DesignRepository $designRepository,
        private ProductRepository $productRepository
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

    #[Route('/products', name: 'admin_products')]
    public function productPage()
    {
        return $this->render('admin/products.html.twig', [
            'products' => $this->productRepository->findAll()
        ]);
    }

    #[Route('/products/delete/{id}', name: 'product_delete')]
    public function deleteProduct(Request $request, int $id)
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);

        $this->em->remove($product);
        $this->em->flush();
        $this->addFlash('success', 'Product has been deleted');

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