<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegisterType;
use App\Repository\DesignRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private ProductRepository $productRepository,
        private DesignRepository $designRepository
    )
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
            ]
        );
    }

//    #[Route('/login', name: 'app_login')]
//    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
//    {
//        $user = new User();
//        $form = $this->createForm(LoginType::class, $user, [
//            'action' => $this->generateUrl('app_login'),
//        ]);
//        $form->handleRequest($request);
//
//        $lastUsername = $authenticationUtils->getLastUsername();
//        $error = $authenticationUtils->getLastAuthenticationError();
//
//        if (!$error && $form->isSubmitted() && $form->isValid()) {
//            return $this->redirectToRoute('main', [
//                'design' => $this->designRepository->findOneBy([]),
//                'products' => $this->productRepository->findAll()
//            ]);
//        }
//
//        return $this->render('_modal.html.twig', [
//            'form' => $form->createView(),
//            'last_username' => $lastUsername,
//            'error' => $error,
//            'title' => 'Login'
//        ]);
//    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user, [
            'action' => $this->generateUrl('app_register'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $duplicateUser = $this->userRepository->findOneBy(['username' => $form->get('username')->getData()]);
            $duplicateEmail = $this->userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if (!$duplicateUser && !$duplicateEmail) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $user->setRoles(['ROLE_USER']);

                $this->em->persist($user);
                $this->em->flush();

                $this->addFlash('success', 'User ' . $user->getUsername() . ' created, you can now sign in');
                return $this->redirectToRoute('main');
            }

            $message = "This username already exists";
            if ($duplicateEmail) {
                $message = "This email is already registered";
            }
            $this->addFlash('danger', $message);
            return $this->redirectToRoute('app_register');
        }

        return $this->render('_modal.html.twig', [
            'form' => $form->createView(),
            'title' => 'Register'
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
