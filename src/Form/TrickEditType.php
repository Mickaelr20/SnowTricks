<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class TrickEditType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('thumbnail', FileType::class, [
				'label' => 'Image primaire (png / jpeg)',
				'mapped' => false,
				'required' => false,
				'constraints' => [
					new Required(),
					new File([
						'maxSize' => '10m',
						'mimeTypes' => [
							'image/png',
							'image/jpeg',
						],
						'mimeTypesMessage' => 'Merci d\'envoyer une image png ou jpeg',
					]),
				],
			])
			->add('name', TextType::class, [
				'constraints' => [
					new NotBlank(),
					new Length(['max' => 255]),
				],
			])
			->add('slug', TextType::class, [
				'constraints' => [
					new NotBlank(),
					new Length(['max' => 255]),
				],
			])
			->add('category', EntityType::class, [
				'class' => TrickCategory::class,
				'choice_label' => 'name',
				'label' => 'Catégorie',
			])
			->add('videos', CollectionType::class, [
				'label' => 'Videos',
				'entry_type' => VideoType::class,
				'entry_options' => ['label' => false],
				'by_reference' => false,
				'allow_add' => true,
				'allow_delete' => true,
			])
			->add('images', CollectionType::class, [
				'label' => 'Images',
				'entry_type' => ImageType::class,
				'entry_options' => ['label' => false],
				'by_reference' => false,
				'allow_add' => true,
				'allow_delete' => true,
			])
			->add('description', TextareaType::class);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Trick::class,
			'constraints' => [
				new UniqueEntity('name', 'Ce nom est déjà pris.'),
				new UniqueEntity('slug', 'Ce slug existe déjà.'),
			],
		]);
	}
}
