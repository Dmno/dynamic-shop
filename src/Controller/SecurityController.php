<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegisterType;
use App\Repository\DesignRepository;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private UserAuthenticatorInterface $userAuthenticator,
        private FormLoginAuthenticator $formLoginAuthenticator,
        private UserPasswordHasherInterface $passwordHasher,
        private DesignRepository $designRepository
    )
    {
    }

    #[Route(path: '/login-action', name: 'auth_login')]
    public function authenticateUserLogin(Request $request): Response
    {
        $email = ($request->request->get('email'));
        $password = ($request->request->get('password'));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['status' => 'Invalid email']);
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['status' => 'No such user']);
        }

        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['status' => 'Invalid credentials']);
        }

        $this->userAuthenticator->authenticateUser(
            $user,
            $this->formLoginAuthenticator,
            $request
        );

        return new JsonResponse(['status' => 'Signed in']);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $email = $request->get('email');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $email ?: $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error
            ]
        );
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userCount = count($this->userRepository->findAll());
            $duplicateUser = $this->userRepository->findOneBy(['username' => $form->get('username')->getData()]);
            $duplicateEmail = $this->userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if (!$duplicateUser && !$duplicateEmail) {
                $user->setPassword(
                    $this->passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $userRole = ['ROLE_USER'];
                $route = 'main';
                if (!$userCount) {
                    $userRole = ['ROLE_ADMIN'];
                    $route = 'admin_main';
                }
                $user->setRoles($userRole);

                $this->em->persist($user);
                $this->em->flush();

                $this->userAuthenticator->authenticateUser(
                    $user,
                    $this->formLoginAuthenticator,
                    $request
                );

                return $this->redirectToRoute($route);
            }

            $message = "This username already exists";
            if ($duplicateEmail) {
                $message = "This email is already registered";
            }
            $this->addFlash('danger', $message);
            return $this->redirectToRoute('app_register');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'design' => $this->designRepository->getDesignWithJoins(),
            'title' => 'Register'
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}