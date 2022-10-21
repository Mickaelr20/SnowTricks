<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Form\UserPasswordType;
use App\Form\UserSettingsType;
use App\Form\UserSignupType;
use App\Repository\UserRepository;
use App\User\CreateUserInterface;
use App\User\EditPasswordUserInterface;
use App\User\EditProfileUserInterface;
use App\User\ForgotPasswordUserInterface;
use App\User\ResetPasswordUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
	#[Route('/signup', name: 'app_signup', methods: ['GET', 'POST'])]
	public function signup(Request $request, CreateUserInterface $createUser): Response
	{
		$user = new User();
		$form = $this->createForm(UserSignupType::class, $user);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$createUser($user);
			$this->addFlash('success', 'Inscription effectuée !');

			return $this->redirectToRoute('app_home');
		}

		return $this->render('user/signup.html.twig', [
			'form' => $form->createView(),
			'page_title' => 'S\'enregistrer',
		]);
	}

	#[Route(path: '/login', name: 'app_login')]
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		if ($this->getUser()) {
			$this->addFlash('success', 'Vous êtes déjà connecté');

			return $this->redirectToRoute('app_home');
		}
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('user/login.html.twig', [
			'last_username' => $lastUsername,
			'error' => $error,
			'page_title' => 'Se connecter',
		]);
	}

	#[Route(path: '/logout', name: 'app_logout')]
	public function logout(): void
	{
		throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
	}

	#[Route('/user/settings', name: 'app_user_settings', methods: ['GET', 'POST'])]
	public function settings(Request $request, UserRepository $repo, EditProfileUserInterface $editUser, EditPasswordUserInterface $editPassword): Response
	{
		$username = $this->getUser()->getUserIdentifier();
		$user = $repo->get($username);
		$settingsForm = $this->createForm(UserSettingsType::class, $user);

		$settingsForm->handleRequest($request);
		if ($settingsForm->isSubmitted() && $settingsForm->isValid()) {
			$editUser($user, $settingsForm->get('profilePicture')->getData());
			$this->addFlash('success', 'Modification(s) effectuée(s) !');

			return $this->redirectToRoute('app_user_settings', ['active_tab' => 'profile']);
		}

		$passwordForm = $this->createForm(UserPasswordType::class);
		$passwordForm->handleRequest($request);
		if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
			$editPassword($user, $passwordForm->get('newPassword')->get('first')->getData());
			$this->addFlash('success', 'Mot de passe modifié');

			return $this->redirectToRoute('app_user_settings', ['active_tab' => 'password']);
		}

		return $this->renderForm('user/settings.html.twig', [
			'page_title' => 'Mon compte',
			'settings_form' => $settingsForm,
			'password_form' => $passwordForm,
			'active_tab' => $request->query->get('active_tab') ?? 'profile',
		]);
	}

	#[Route('/forgot_password', name: 'app_user_forgot_password', methods: ['GET', 'POST'])]
	public function forgot_password(Request $request, ForgotPasswordUserInterface $forgotPassword): Response
	{
		$user = new User();
		$form = $this->createForm(ForgotPasswordType::class, $user);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$forgotPassword($user->getEmail());
			$this->addFlash('success', "Demande récupération envoyé !\nSi votre adresse email est présente dans notre base, nous vous enverrons un lien de récupération sur celle - ci");

			return $this->redirectToRoute('app_home');
		}

		return $this->renderForm('forgot_password.html.twig', [
			'form' => $form,
			'page_title' => 'Mot de passe oublié',
		]);
	}

	#[Route('/reset_password/{resetPasswordToken}', name: 'app_user_reset_password', methods: ['GET', 'POST'])]
	public function reset_password(User $user, Request $request, ResetPasswordUserInterface $resetPassword): Response
	{
		$nowDate = new \DateTime();
		if ($nowDate > $user->getResetPasswordExpire()) {
			throw new \Exception('Lien expiré');
		}

		$form = $this->createForm(ResetPasswordType::class, $user);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$resetPassword($user, $form->get('password')->get('first')->getData());
			$this->addFlash('success', 'Mot de passe modifié !');

			return $this->redirectToRoute('app_home');
		}

		return $this->renderForm('reset_password.html.twig', [
			'form' => $form,
			'page_title' => 'Récupération du compte',
		]);
	}
}
