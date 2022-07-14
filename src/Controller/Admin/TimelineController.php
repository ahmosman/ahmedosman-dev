<?php

namespace App\Controller\Admin;

use App\Controller\Abstract\AbstractTranslatableCrudController;
use App\Entity\Timeline;
use App\Form\TimelineType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/timeline')]
class TimelineController extends AbstractTranslatableCrudController
{
    private string $entityName;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        parent::__construct($requestStack, $entityManager);
        $this->entityName = 'Timeline';
    }

    #[Route('/new', name: 'timeline_new')]
    public function new(Request $request): Response
    {
        $this->setTranslatableEntity(new Timeline());

        $form = $this->createForm(TimelineType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);
            return $this->redirectToRoute('dashboard_timeline', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_new.html.twig', [
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    #[Route('/{id}/edit', name: 'timeline_edit')]
    public function edit(Request $request, Timeline $timeline): Response
    {
        $this->setTranslatableEntity($timeline);
        $form = $this->createForm(TimelineType::class, $this->createFormData());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);
            return $this->redirectToRoute('dashboard_timeline', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_edit.html.twig', [
            'entity' => $timeline,
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    public function createFormData(): array
    {
        return [
            'title' => $this->entityTranslation->getTitle(),
            'subtitle' => $this->entityTranslation->getSubtitle(),
            'link' => $this->entity->getLink(),
            'dateRange' => $this->entityTranslation->getDateRange(),
            'date' => $this->entity->getDate(),
            'timelineCategory' => $this->entity->getTimelineCategory()
        ];
    }

    #[Route('/{id}/delete', name: 'timeline_delete')]
    public function delete(Timeline $timeline)
    {
        $this->entityManager->remove($timeline);
        $this->entityManager->flush();

        return $this->redirectToRoute('dashboard_timeline', [], Response::HTTP_SEE_OTHER);
    }

    public function setTranslatableEntityFieldsFromForm($form)
    {
        $this->entity->setDate($form['date']->getData());
        $this->entity->setLink($form['link']->getData());
        $this->entity->setTimelineCategory($form['timelineCategory']->getData());
        $this->entityTranslation->setTitle($form['title']->getData());
        $this->entityTranslation->setSubtitle($form['subtitle']->getData());
        $this->entityTranslation->setDateRange($form['dateRange']->getData());
    }

}