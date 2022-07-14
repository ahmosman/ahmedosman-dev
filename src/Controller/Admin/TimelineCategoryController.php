<?php

namespace App\Controller\Admin;

use App\Controller\Abstract\AbstractTranslatableCrudController;
use App\Entity\TimelineCategory;
use App\Form\TimelineCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/timeline-category')]
class TimelineCategoryController extends AbstractTranslatableCrudController
{
    private string $entityName;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        parent::__construct($requestStack, $entityManager);
        $this->entityName = 'Timeline category';
    }

    public function setTranslatableEntityFieldsFromForm($form)
    {
        $this->entityTranslation->setName($form['name']->getData());
    }

    #[Route('/new', name: 'timeline-category_new')]
    public function new(Request $request): Response
    {
        $this->setTranslatableEntity(new TimelineCategory());

        $form = $this->createForm(TimelineCategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);
            return $this->redirectToRoute('dashboard_timeline-category', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_new.html.twig', [
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    #[Route('/{id}/edit', name: 'timeline-category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TimelineCategory $timelineCategory): Response
    {
        $this->setTranslatableEntity($timelineCategory);
        $form = $this->createForm(TimelineCategoryType::class, $this->createFormData());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);
            return $this->redirectToRoute('dashboard_timeline-category', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_edit.html.twig', [
            'entity' => $timelineCategory,
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    public function createFormData(): array
    {
        return [
            'name' => $this->entityTranslation->getName()
        ];
    }

    #[Route('/{id}/delete', name: 'timeline-category_delete', requirements:["id" => "\d+"])]
    public function delete(TimelineCategory $timelineCategory)
    {
        $this->entityManager->remove($timelineCategory);
        $this->entityManager->flush();

        return $this->redirectToRoute('dashboard_timeline-category', [], Response::HTTP_SEE_OTHER);
    }
}