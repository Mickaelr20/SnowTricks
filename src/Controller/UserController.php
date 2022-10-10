<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserSignupType;
use App\Form\UserSettingsType;
use App\Form\UserPasswordType;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\User\CreateUserInterface;

class UserController extends AbstractController
{
    #[Route('/signup', name: 'app_signup', methods: ["GET", "POST"])]
    public function signup(Request $request, CreateUserInterface $createUser): Response
    {
        $user = new User();
        $form = $this->createForm(UserSignupType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $createUser($user);
            $this->addFlash('success', "Inscription effectuée !");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/signup.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'S\'enregistrer'
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('success', "Vous êtes déjà connecté");
            return $this->redirectToRoute('app_home');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' =>
            $error,
            'page_title' => 'Se connecter'
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/user/settings', name: 'app_user_settings', methods: ["GET", "POST"])]
    public function settings(Request $request, UserRepository $repo, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher): Response
    {
        $activeTab = $request->query->get("active_tab") ?? "profile";
        $username = $this->getUser()->getUserIdentifier();
        $user = $repo->get($username);
        $settingsForm = $this->createForm(UserSettingsType::class, $user);

        $settingsForm->handleRequest($request);
        if ($settingsForm->isSubmitted()) {
            $activeTab = "profile";
        }
        if ($settingsForm->isSubmitted() && $settingsForm->isValid()) {
            $profilePictureFile = $settingsForm->get('profilePicture')->getData();

            if ($profilePictureFile) {
                //Supprimer l'ancienne photo de profile si elle existe
                $oldUser = $repo->get($username);
                if (!empty($oldUser->getProfilePictureFilename())) {
                    $oldProfilePicture = $this->getParameter('profile_pictures_directory') . "/" . $oldUser->getProfilePictureFilename();
                    if (file_exists($oldProfilePicture) && is_file($oldProfilePicture)) {
                        unlink($oldProfilePicture);
                    }
                }

                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePictureFile->guessExtension();

                $profilePictureFile->move(
                    $this->getParameter('profile_pictures_directory'),
                    $newFilename
                );

                $user->setProfilePictureFilename($newFilename);
            }
            $repo->add($user, true);
            $this->addFlash('success', "Modification(s) effectuée(s) !");
            return $this->redirectToRoute('app_user_settings');
        }

        $passwordForm = $this->createForm(UserPasswordType::class);
        $passwordForm->handleRequest($request);
        if ($passwordForm->isSubmitted()) {
            $activeTab = "password";
        }
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $oldPassword = $passwordForm->get("oldPassword")->getData();
            $newPasswordFirst = $passwordForm->get("newPassword")->get('first')->getData();

            if ($passwordHasher->isPasswordValid($user, $oldPassword)) {
                $newHashedPassword = $passwordHasher->hashPassword($user, $newPasswordFirst);
                $user->setPassword($newHashedPassword);
                $repo->add($user, true);
                $this->addFlash('success', "Mot de passe modifié");
                return $this->redirectToRoute('app_user_settings', ['active_tab' => $activeTab]);
            }
            $this->addFlash('danger', "Mot de passe actuel incorrect");
        }

        return $this->renderForm('user/settings.html.twig', [
            'page_title' => 'Mon compte',
            'settings_form' => $settingsForm,
            'password_form' => $passwordForm,
            'active_tab' => $activeTab
        ]);
    }

    #[Route('/forgot_password', name: 'app_user_forgot_password', methods: ["GET", "POST"])]
    public function forgot_password(Request $request, UserRepository $repo, MailerInterface $mailer): Response
    {
        $user = new User();

        $form = $this->createForm(ForgotPasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repo->getFromEmail($user->getEmail());

            if (!empty($user)) {
                $token = $this->generateToken();

                $user->setResetPasswordToken($token);
                $user->setResetPasswordCreated(new \Datetime());
                $user->setResetPasswordExpire((new \Datetime())->add(new \DateInterval('P1D')));
                $repo->add($user, true);

                $email = (new TemplatedEmail())
                    ->from('noreply@snowtricks.com')
                    ->to($user->getEmail())
                    ->subject('Récupération du compte')
                    ->htmlTemplate('email/user/forgot_password.html.twig')
                    ->context([
                        "user" => $user,
                        "token" => $token
                    ]);

                $mailer->send($email);
            }

            $this->addFlash('success', "Demande récupération envoyé !\nSi votre adresse email est présente dans notre base, nous vous enverrons un lien de récupération sur celle - ci");
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('forgot_password.html.twig', [
            'form' => $form,
            'page_title' => 'Mot de passe oublié'
        ]);
    }

    #[Route('/reset_password/{resetPasswordToken}', name: 'app_user_reset_password', methods: ["GET", "POST"])]
    public function reset_password(User $user, Request $request, UserRepository $repo, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        $now_date = new \Datetime();
        if ($now_date > $user->getResetPasswordExpire()) {
            throw new \Exception("Lien expiré");
        }

        $form = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPasswordFirst = $form->get("password")->get('first')->getData();
            $newHashedPassword = $passwordHasher->hashPassword($user, $newPasswordFirst);
            $user->setPassword($newHashedPassword);

            $user->setResetPasswordToken(null);
            $user->setResetPasswordCreated(null);
            $user->setResetPasswordExpire(null);

            $repo->add($user, true);

            $email = (new TemplatedEmail())
                ->from('noreply@snowtricks.com')
                ->to($user->getEmail())
                ->subject('Récupération du compte')
                ->htmlTemplate('email/user/password_reset.html.twig')
                ->context([
                    "user" => $user
                ]);

            $mailer->send($email);

            $this->addFlash('success', "Mot de passe modifié !");
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('reset_password.html.twig', [
            'form' => $form,
            'page_title' => 'Récupération du compte'
        ]);
    }

    private function generateToken($tokenLength = 100)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $token = '';
        for ($i = 0; $i < $tokenLength; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }

        return $token;
    }
}
