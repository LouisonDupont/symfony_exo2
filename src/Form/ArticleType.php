<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'article",
                'attr' => [
                    'placeholder' => "Saisir le titre de l'article",
                    'class' => 'form-control'
                ]
            ])
            ->add('author', TextType::class, [
                'label' => "Nom de l'auteur",
                'attr' => [
                    'placeholder' => "Saisir le nom de l'auteur",
                    'class' => 'form-control'
                ]
            ])
            ->add('resume', TextType::class, [
                'label' => "Résumé de l'article",
                'attr' => [
                    'placeholder' => "Saisir le résumé",
                    'class' => 'form-control'
                ]
            ])
            ->add('contenu', TextType::class, [
                'label' => "Contenu de l'article",
                'attr' => [
                    'placeholder' => "Saisissez votre contenu",
                    'class' => 'form-control'
                ]
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
