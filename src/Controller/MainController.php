<?php

namespace App\Controller;

use App\Entity\Heading;
use App\Service\TranslatableContentException;
use App\Service\TranslatableContentGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractTranslatablePageContentController
{

    private array $headings;

    /**
     * @throws TranslatableContentException
     */
    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, TranslatableContentGenerator $contentGenerator)
    {
        parent::__construct($requestStack, $entityManager, $contentGenerator);
        $this->headings = $this->contentGenerator->generateContentTextIDArray(Heading::class, $this->locale);
    }

    #[Route('/')]
    public function indexNoLocale(): Response
    {
        return $this->redirectToRoute('homepage', ['_locale' => 'pl']);
    }

    #[Route('/{_locale<%app.supported_locales%>}/', name: 'homepage')]
    public function homepage(): Response
    {

        return $this->render('main/home.html.twig', [
            'headings' => $this->headings
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('main/about.html.twig', [
            'headings' => $this->headings
        ]);
    }

}
