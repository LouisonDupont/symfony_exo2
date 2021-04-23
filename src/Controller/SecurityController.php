<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder )
    {
        // Methodes nécessaires pour le bon déroulement de ma création de form / password
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // ICI, je déclare qu'après ma connexion, si mon user est égale a Role Admin ou Role Super Admin, je suis redirigé vers la page admin
        if ($this->isGranted('ROLE_ADMIN')) {
             return $this->redirectToRoute('admin');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="register")
     */

    // Pour enregistrer un nouvelle user
    public function register(Request $request): Response
    {
        $user = new User();
        // Je lie au bon form (RegisterType)
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // Je recupere les donnes de ma BDD
            $user = $form->getData();

            // Je prend le mot de passe et je l'encode pour ensuite le re-set dans mon objet
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            $this ->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('home');

        }

        return $this->render(
            'security/register.html.twig',
            ['form_register' => $form->createView()]
        );
    }
}
