<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractTranslatableCrudController extends AbstractController implements TranslatableCrudControllerInterface
{
    protected string $locale;
    protected EntityManagerInterface $entityManager;
    protected TranslatableInterface $entity;
    protected TranslationInterface $entityTranslation;
    protected $form;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $entityManager
    ) {
        $this->locale = $requestStack->getCurrentRequest()->getLocale();
        $this->entityManager = $entityManager;
    }

    protected function setTranslatableEntity(TranslatableInterface $entity): void
    {
        $this->entity = $entity;
        $this->entityTranslation = $entity->translate($this->locale);
    }

//    protected function mergeNewTranslationsAndFlush(): void
//    {
//        $this->entity->mergeNewTranslations();
//        $this->entityManager->persist($this->entity);
//        $this->entityManager->flush();
//    }

    protected function setTranslatableFieldsAndFlushForm($form): void
    {
        $this->setTranslatableEntityFieldsFromForm($form);
        $this->entity->mergeNewTranslations();
        $this->entityManager->persist($this->entity);
        $this->entityManager->flush();
    }
}