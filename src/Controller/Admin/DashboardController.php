<?php

namespace App\Controller\Admin;

use App\Controller\AbstractLocaleController;
use App\Repository\HeadingRepository;
use App\Repository\ParagraphRepository;
use App\Service\TranslatableDashboardFieldsGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/dashboard')]
class DashboardController extends AbstractLocaleController
{

    private array $paths = [];

    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        return $this->redirectToRoute('dashboard_heading');
    }

    #[Route('/heading', name: 'dashboard_heading')]
    public function heading(HeadingRepository $headingRepository): Response
    {
        $headings = (new TranslatableDashboardFieldsGenerator($headingRepository->findAll(), ['id', 'textID'], ['name'], $this->locale))->generate();
        $this->paths = [
            'new' => 'heading_new',
            'edit' => 'heading_edit',
            'delete' => 'heading_delete',
        ];

        return $this->render('dashboard/heading.html.twig', [
            'paths' => $this->paths,
            'headings' => $headings
        ]);
    }

    #[Route('/paragraph', name: 'dashboard_paragraph')]
    public function paragraph(ParagraphRepository $paragraphRepository): Response
    {

        $paragraphs = (new TranslatableDashboardFieldsGenerator($paragraphRepository->findAll(), ['id', 'textID'], ['title', 'description'], $this->locale))->generate();

        $this->paths = [
            'new' => 'paragraph_new',
            'edit' => 'paragraph_edit',
            'delete' => 'paragraph_delete',
        ];

        return $this->render('dashboard/paragraph.html.twig', [
            'paths' => $this->paths,
            'paragraphs' => $paragraphs
        ]);
    }

}
