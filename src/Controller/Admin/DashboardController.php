<?php

namespace App\Controller\Admin;

use App\Controller\Abstract\AbstractLocaleController;
use App\Repository\CredentialRepository;
use App\Repository\HeadingRepository;
use App\Repository\ParagraphRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectSlideRepository;
use App\Repository\TimelineCategoryRepository;
use App\Repository\TimelineRepository;
use App\Repository\ToolRepository;
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
        $headings = $headingRepository->findAll();
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

        $paragraphs = $paragraphRepository->findAll();

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

    #[Route('/tool', name: 'dashboard_tool')]
    public function tool(ToolRepository $toolRepository): Response
    {
        $tools = $toolRepository->findAllOrderBy('orderValue');

        $this->paths = [
            'new' => 'tool_new',
            'edit' => 'tool_edit',
            'delete' => 'tool_delete'
        ];

        return $this->render('dashboard/tool.html.twig', [
            'paths' => $this->paths,
            'tools' => $tools
        ]);
    }

    #[Route('/timeline-category', name: 'dashboard_timeline-category')]
    public function timelineCategory(TimelineCategoryRepository $timelineCategoryRepository): Response
    {
        $timelineCategories = $timelineCategoryRepository->findAll();

        $this->paths = [
            'new' => 'timeline-category_new',
            'edit' => 'timeline-category_edit',
            'delete' => 'timeline-category_delete'
        ];

        return $this->render('dashboard/timelineCategory.html.twig', [
            'paths' => $this->paths,
            'timelineCategories' => $timelineCategories
        ]);
    }

    #[Route('/timeline', name: 'dashboard_timeline')]
    public function timeline(TimelineRepository $timelineRepository)
    {
        $timelines = $timelineRepository->findAll();
        $this->paths = [
            'new' => 'timeline_new',
            'edit' => 'timeline_edit',
            'delete' => 'timeline_delete'
        ];
        return $this->render('dashboard/timeline.html.twig', [
            'paths' => $this->paths,
            'timelines' => $timelines
        ]);
    }

    #[Route('/credential', name: 'dashboard_credential')]
    public function credential(CredentialRepository $credentialRepository)
    {
        $credentials = $credentialRepository->findAll();
        $this->paths = [
            'new' => 'credential_new',
            'edit' => 'credential_edit',
            'delete' => 'credential_delete'
        ];
        return $this->render('dashboard/credential.html.twig', [
            'paths' => $this->paths,
            'credentials' => $credentials
        ]);
    }

    #[Route('/project-slide', name: 'dashboard_project-slide')]
    public function projectSlide(ProjectSlideRepository $projectSlideRepository)
    {
        $projectSlides = $projectSlideRepository->findAll();
        $this->paths = [
            'new' => 'project-slide_new',
            'edit' => 'project-slide_edit',
            'delete' => 'project-slide_delete'
        ];
        return $this->render('dashboard/projectSlide.html.twig', [
            'paths' => $this->paths,
            'projectSlides' => $projectSlides
        ]);
    }

    #[Route('/project', name: 'dashboard_project')]
    public function project(ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findAll();
        $this->paths = [
            'new' => 'project_new',
            'edit' => 'project_edit',
            'delete' => 'project_delete'
        ];
        return $this->render('dashboard/project.html.twig', [
            'paths' => $this->paths,
            'projects' => $projects
        ]);
    }

}
