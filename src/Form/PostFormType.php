<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('title', TextType::class, [
				'attr' => [
					'class' => 'form',
					'placeholder' => 'Titre du post...'
				],
				'label' => "Titre du post",
				'constraints' => [
					new NotBlank([
						"message" => "Champs obligatoire"
					]),
					new Length([
						'min' => 3,
						'max' => 20,
						'minMessage' => "Minimum {{ limit }} caractères",
						'maxMessage' => "Maximum {{ limit }} caractères",

					])
				]

			])
			->add('content', TextareaType::class, [
				'attr' => [
					'class' => 'form'
				],
				'label' => "Titre du post",
				'constraints' => [
					new NotBlank([
						"message" => "Champs obligatoire"
					]),
					new Length([
						'min' => 3,
						'max' => 300,
						'minMessage' => "Minimum {{ limit }} caractères",
						'maxMessage' => "Maximum {{ limit }} caractères",

					])
				]
			])
			->add('url_img', FileType::class, [
				'mapped' => false,
				'attr' => [
					'class' => 'form'
				],
				'label' => "Votre image",
				'constraints' => [
					// new NotBlank([
					// 	"message" => "Champs obligatoire"
					// ]),
					new File([
						'maxSize' => '3M',
						'maxSizeMessage' => 'Votre fichier ne doit pas depasser {{ limit }}',
						'extensions' => [
							'jpeg', 'avif', 'png', 'jpg'
						],
						'extensionsMessage' => 'Type de fichier accepter {{ extensions }}',
					])
				]
			])
			->add('author', TextType::class, [
				'attr' => [
					'class' => 'form'
				],
				'label' => "Titre du post",
				'constraints' => [
					new NotBlank([
						"message" => "Champs obligatoire"
					]),
					new Length([
						'min' => 3,
						'max' => 20,
						'minMessage' => "Minimum {{ limit }} caractères",
						'maxMessage' => "Maximum {{ limit }} caractères",

					])
				]
			])
			->add('category', TextType::class, [
				'attr' => [
					'class' => 'form'
				],
				'label' => "Titre du post",
				'constraints' => [
					new NotBlank([
						"message" => "Champs obligatoire"
					]),
					new Length([
						'min' => 3,
						'max' => 20,
						'minMessage' => "Minimum {{ limit }} caractères",
						'maxMessage' => "Maximum {{ limit }} caractères",

					])
				]
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Post::class,
		]);
	}
}
