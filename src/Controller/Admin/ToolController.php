<?php

namespace App\Controller\Admin;

use App\Entity\Tool;
use App\Form\ToolType;
use App\Repository\ToolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/tool')]
class ToolController extends AbstractController
{
    private string $entityName;
    private ToolRepository $repository;

    public function __construct(ToolRepository $repository)
    {
        $this->entityName = 'Tool';
        $this->repository = $repository;
    }

    #[Route('/new', name: 'tool_new')]
    public function new(Request $request)
    {
        $tool = new Tool();

        $form = $this->createForm(ToolType::class, $tool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($tool, true);
            return $this->redirectToRoute('dashboard_tool', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_new.html.twig', [
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    #[Route('/{id}/edit', name: 'tool_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tool $tool): Response
    {
        $form = $this->createForm(ToolType::class, $tool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($tool, true);
            return $this->redirectToRoute('dashboard_tool', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_edit.html.twig', [
            'entity' => $tool,
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    #[Route('/{id}/delete', name: 'tool_delete')]
    public function delete(Tool $tool)
    {
        $this->repository->remove($tool, true);
        return $this->redirectToRoute('dashboard_tool', [], Response::HTTP_SEE_OTHER);
    }
}