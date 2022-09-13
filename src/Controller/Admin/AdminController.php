<?php

namespace App\Controller\Admin;

use App\Entity\Design;
use App\Form\DesignType;
use App\Repository\DesignRepository;
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
        private DesignRepository $designRepository
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