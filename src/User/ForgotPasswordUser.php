<?php

namespace App\User;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

final class ForgotPasswordUser implements ForgotPasswordUserInterface
{
	public function __construct(private UserRepository $repo, private MailerInterface $mailer)
	{
	}

	public function __invoke(string $email): void
	{
		$user = $this->repo->getFromEmail($email);

		if (!empty($user)) {
			$token = $this->generateToken();

			$user->setResetPasswordToken($token);
			$user->setResetPasswordCreated(new \DateTime());
			$user->setResetPasswordExpire((new \DateTime())->add(new \DateInterval('P1D')));
			$this->repo->add($user, true);

			$email = (new TemplatedEmail())
				->from('noreply@snowtricks.com')
				->to($user->getEmail())
				->subject('Récupération du compte')
				->htmlTemplate('email/user/forgot_password.html.twig')
				->context([
					'user' => $user,
					'token' => $token,
				]);

			$this->mailer->send($email);
		}
	}

	private function generateToken($tokenLength = 100)
	{
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$token = '';
		for ($i = 0; $i < $tokenLength; ++$i) {
			$token .= $characters[rand(0, $charactersLength - 1)];
		}

		return $token;
	}
}
