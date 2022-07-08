<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/dashboard')]
class DashboardController extends AbstractController
{
    private array $paths = [];
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {

        return $this->render('dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/heading', name: 'dashboard_heading')]
    public function heading(): Response
    {
        $this->paths = [
            'new' => 'heading_new',
            'edit' => 'heading_edit',
            'delete' => 'heading_delete'
        ];

        return $this->render('dashboard/heading.html.twig', [
            'paths' => $this->paths
        ]);
    }

    #[Route('/paragraph', name: 'dashboard_paragraph')]
    public function paragraph(): Response
    {
        $this->paths = [
            'new' => 'paragraph_new',
            'edit' => 'paragraph_edit',
            'delete' => 'paragraph_delete'
        ];
        return $this->render('dashboard/paragraph.html.twig', [
            'paths' => $this->paths
        ]);
    }
}
