<?php

namespace App\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResetPasswordUser implements ResetPasswordUserInterface
{
	public function __construct(
		private UserRepository $repo,
		private UserPasswordHasherInterface $passwordHasher,
		private MailerInterface $mailer
	) {
	}

	public function __invoke(User $user, string $password): void
	{
		$newHashedPassword = $this->passwordHasher->hashPassword($user, $password);
		$user->setPassword($newHashedPassword);

		$user->setResetPasswordToken(null);
		$user->setResetPasswordCreated(null);
		$user->setResetPasswordExpire(null);

		$this->repo->add($user, true);

		$email = (new TemplatedEmail())
			->from('noreply@snowtricks.com')
			->to($user->getEmail())
			->subject('Récupération du compte')
			->htmlTemplate('email/user/password_reset.html.twig')
			->context([
				'user' => $user,
			]);

		$this->mailer->send($email);
	}
}
