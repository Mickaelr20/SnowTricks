<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class UserSettingsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('profilePicture', FileType::class, [
				'label' => 'Photo de profile',
				'mapped' => false,
				'required' => false,
				'constraints' => [
					new Required(),
					new File([
						'maxSize' => '5m',
						'mimeTypes' => [
							'image/png',
							'image/jpeg',
						],
						'mimeTypesMessage' => 'Merci d\'envoyer une image png ou jpeg',
					]),
				],
			])
			->add('username', TextType::class, [
				'constraints' => [
					new notBlank(),
				],
			])
			->add('email', EmailType::class, [
				'constraints' => [
					new notBlank(),
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => User::class,
			'constraints' => [
				new UniqueEntity('email', 'Cette email est déjà prise.'),
				new UniqueEntity('username', 'Ce pseudo est déjà pris.'),
			],
		]);
	}
}
