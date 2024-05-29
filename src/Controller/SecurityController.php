<?php

namespace App\Controller;

use App\Entity\Factory\UserRegistrationFactory;
use App\Form\SignupType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(name: 'app_')]
class SecurityController extends AbstractController
{
    #[Route(
        '/login',
        name: 'login',
        options: [
            'sitemap' => [
                'section' => 'public',
                'changefreq' => UrlConcrete::CHANGEFREQ_MONTHLY,
            ]
        ],
        methods: ['GET']
    )]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/signin.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(
        '/register',
        name: 'register',
        options: [
            'sitemap' => [
                'section' => 'public',
                'changefreq' => UrlConcrete::CHANGEFREQ_MONTHLY,
            ]
        ],
        methods: ['GET', 'POST']
    )]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(SignupType::class);

        // handle the form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userRegistrationFactory = new UserRegistrationFactory($passwordHasher);
            $data = $form->getData();
            $user = $userRegistrationFactory->create($data['username'], $data['plainPassword']);

            try {
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_login');
            } catch (UniqueConstraintViolationException $e) {
                $form->get('username')->addError(new FormError('This username is already taken.'));
                return $this->render('security/signup.html.twig', [
                    'form' => $form,
                ]);
            }
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
