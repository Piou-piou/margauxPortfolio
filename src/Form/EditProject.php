<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProject extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('titleTag', TextType::class, [
				'label' => 'Titre de la page (150 caractères max)',
				'label_attr' => [
					'class' => 'label'
				],
				'attr' => [],
				'required' => true
			])
			->add('descriptionTag', TextareaType::class, [
				'label' => 'Description de la page (253 caractères max)',
				'label_attr' => [
					'class' => 'label label-textarea'
				],
				'attr' => [],
				'required' => true
			])
			->add('url', TextType::class, [
				'label' => 'Url de la page',
				'label_attr' => [
					'class' => 'label'
				],
				'attr' => [],
				'required' => true
			])
			->add('title', TextType::class, [
				'label' => 'Titre du projet',
				'label_attr' => [
					'class' => 'label'
				],
				'attr' => [],
				'required' => true
			])
			->add('article', TextareaType::class, [
				'label' => 'Description du projet',
				'label_attr' => [
					'class' => 'label label-textarea'
				],
				'attr' => [
					'class' => "tinymce",
					'readonly' => true
				],
				'required' => true
			])
			->add('submit', SubmitType::class, [
				'label' => 'Valider',
				'attr' => []
			]);
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => \App\Entity\Project::class,
		]);
	}
}