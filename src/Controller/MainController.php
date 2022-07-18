<?php

namespace App\Controller;

use App\Controller\Abstract\AbstractTranslatablePageContentController;
use App\Entity\Credential;
use App\Entity\Heading;
use App\Entity\Paragraph;
use App\Entity\ProjectSlide;
use App\Entity\TimelineCategory;
use App\Entity\Tool;
use App\Service\TranslatableContentException;
use App\Service\TranslatableContentGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractTranslatablePageContentController
{

    private array $headings;
    private array $paragraphs;

    /**
     * @throws TranslatableContentException
     */
    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, TranslatableContentGenerator $contentGenerator)
    {
        parent::__construct($requestStack, $entityManager, $contentGenerator);
        $this->headings = $this->contentGenerator->generateTranslatableTextIDArrayContent(Heading::class, $this->locale);
        $this->paragraphs = $this->contentGenerator->generateTranslatableTextIDArrayContent(Paragraph::class, $this->locale);
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

    /**
     * @throws TranslatableContentException
     */
    #[Route('/{_locale<%app.supported_locales%>}/about', name: 'about')]
    public function about(): Response
    {
        $tools = $this->entityManager->getRepository(Tool::class)->findAllOrderBy('orderValue');
        $timelineCategories = $this->entityManager->getRepository(TimelineCategory::class)->findAll();
        $credentials = $this->entityManager->getRepository(Credential::class)->findAll();

        return $this->render('main/about.html.twig', [
            'headings' => $this->headings,
            'paragraphs' => $this->paragraphs,
            'tools' => $tools,
            'timelineCategories' => $timelineCategories,
            'credentials' => $credentials
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/portfolio', name: 'portfolio')]
    public function portfolio(): Response
    {
        $projectSlides = $this->entityManager->getRepository(ProjectSlide::class)->findAll();

        return $this->render('main/portfolio.html.twig', [
            'slides' => $projectSlides
        ]);
    }

}
