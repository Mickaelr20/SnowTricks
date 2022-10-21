<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserSignupType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('username', TextType::class, [
				'constraints' => [
					new NotBlank(),
				],
			])
			->add('email', EmailType::class, [
				'constraints' => [
					new NotBlank(),
				],
			])
			->add('password', PasswordType::class, [
				'constraints' => [
					new NotBlank(),
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => User::class,
			'constraints' => [
				new UniqueEntity('username', 'Identifiants déjà utilisées.'),
				new UniqueEntity('email', 'Identifiants déjà utilisées.'),
			],
		]);
	}
}
