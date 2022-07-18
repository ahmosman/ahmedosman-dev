<?php

namespace App\Form;

use App\Entity\TimelineCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TimelineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('subtitle', TextType::class)
            ->add('dateRange', TextType::class)
            ->add('date', DateType::class)
            ->add('link', TextType::class, [
                'required' => false
            ])
            ->add('timelineCategory', EntityType::class, [
                'class' => TimelineCategory::class
            ])
        ;
    }
}
