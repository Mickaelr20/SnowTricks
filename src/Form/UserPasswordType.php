<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserPasswordType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('oldPassword', PasswordType::class, [
				'required' => true,
				'label' => 'Mot de passe actuel',
				'mapped' => false,
				'constraints' => [
					new UserPassword(),
					new NotBlank(),
				],
			])
			->add('newPassword', RepeatedType::class, [
				'type' => PasswordType::class,
				'invalid_message' => 'Les mots de passe ne correspondent pas',
				'required' => true,
				'first_options' => ['label' => 'Nouveau mot de passe'],
				'second_options' => ['label' => 'Répéter nouveau mot de passe'],
				'mapped' => false,
				'constraints' => [
					new NotBlank(),
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
