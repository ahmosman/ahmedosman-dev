<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractLocaleController extends AbstractController
{
    protected string $locale;

    public function __construct(RequestStack $requestStack)
    {
        $this->locale = $requestStack->getCurrentRequest()->getLocale();
    }

}