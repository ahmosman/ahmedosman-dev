<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractTranslatableCrudController extends AbstractLocaleController implements TranslatableCrudControllerInterface
{

    protected EntityManagerInterface $entityManager;
    protected TranslatableInterface $entity;
    protected TranslationInterface $entityTranslation;
    protected $form;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        parent::__construct($requestStack);
        $this->entityManager = $entityManager;
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