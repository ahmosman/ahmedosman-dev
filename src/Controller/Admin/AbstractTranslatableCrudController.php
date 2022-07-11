<?php

namespace App\Controller\Admin;

use App\Controller\AbstractEntityLocaleController;
use Doctrine\ORM\EntityManagerInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractTranslatableCrudController extends AbstractEntityLocaleController implements TranslatableCrudControllerInterface
{
    protected TranslatableInterface $entity;
    protected TranslationInterface $entityTranslation;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        parent::__construct($requestStack, $entityManager);
    }

    protected function setTranslatableEntity(TranslatableInterface $entity): void
    {
        $this->entity = $entity;
        $this->entityTranslation = $entity->translate($this->locale, false);
    }

    protected function setTranslatableFieldsAndFlushForm($form): void
    {
        $this->setTranslatableEntityFieldsFromForm($form);
        $this->entity->mergeNewTranslations();
        $this->entityManager->persist($this->entity);
        $this->entityManager->flush();
    }

}