<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class TrickEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('thumbnail', FileType::class, [
                'label' => 'Image primaire (png / jpeg)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new Required(),
                    new File([
                        'maxSize' => '10m',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Merci d\'envoyer une image',
                    ])
                ],
            ])
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255])
                ]
            ])
            ->add('category', ChoiceType::class, [
                'constraints' => [
                    new NotBlank()
                ],
                'choices' => [
                    '---' => '',
                    'Grab' => 'grab',
                    'Rotation' => 'rotation',
                    'Flip' => 'flip',
                    'Rotation désaxée' => 'misaligned_rotation',
                    'Slide' => 'slide',
                    'One foot' => 'one_foot',
                    'Old school' => 'old_school'
                ]
            ])
            ->add('description', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'constraints' => [
                new UniqueEntity('name', 'Ce nom est déjà pris.')
            ]
        ]);
    }
}
