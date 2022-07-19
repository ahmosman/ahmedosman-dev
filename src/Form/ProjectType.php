<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('subtitle', TextType::class)
            ->add('shortDescription', TextareaType::class)
            ->add('description', TextareaType::class)
            ->add('usedTools', TextareaType::class)
            ->add('githubLink', TextType::class,[
                'required' => false
            ])
            ->add('webLink', TextType::class,[
                'required' => false
            ])
            ->add('orderValue', IntegerType::class)
            ->add('imageFile', FileType::class,[
                'required' => false,
                'constraints' => new Image()
            ])
        ;
    }
}