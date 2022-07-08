<?php

namespace App\Form;

use App\Entity\Paragraph;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParagraphType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('textID')
            ->add('title', TextType::class, [
                'mapped' => false
            ])
            ->add('description', TextType::class, [
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paragraph::class,
        ]);
    }
}
