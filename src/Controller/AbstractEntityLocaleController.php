<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractEntityLocaleController extends AbstractLocaleController
{
    protected EntityManagerInterface $entityManager;
    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        parent::__construct($requestStack);
        $this->entityManager = $entityManager;
    }
}