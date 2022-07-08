<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/dashboard')]
class DashboardController extends AbstractController
{
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
        return $this->render('dashboard/heading.html.twig', [
        ]);
    }

    #[Route('/paragraph', name: 'dashboard_paragraph')]
    public function paragraph(): Response
    {
        return $this->render('dashboard/paragraph.html.twig', [
        ]);
    }
}
