<?php

namespace App\Controller;

use App\Service\TranslatableContentGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractTranslatablePageContentController extends AbstractEntityLocaleController
{
    protected TranslatableContentGenerator $contentGenerator;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, TranslatableContentGenerator $contentGenerator)
    {
        parent::__construct($requestStack, $entityManager);
        $this->contentGenerator = $contentGenerator;
    }
}