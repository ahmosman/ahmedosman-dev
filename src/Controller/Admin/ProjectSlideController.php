<?php

namespace App\Controller\Admin;

use App\Controller\Abstract\AbstractTranslatableCrudController;
use App\Entity\ProjectSlide;
use App\Form\ProjectSlideType;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/project-slide')]
class ProjectSlideController extends AbstractTranslatableCrudController
{
    private string $entityName;
    private UploaderHelper $uploaderHelper;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper)
    {
        parent::__construct($requestStack, $entityManager);
        $this->entityName = 'Project slide';
        $this->uploaderHelper = $uploaderHelper;
    }

    #[Route('/new', name: 'project-slide_new')]
    public function new(Request $request): Response
    {
        $this->setTranslatableEntity(new ProjectSlide());

        $form = $this->createForm(ProjectSlideType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);
            return $this->redirectToRoute('dashboard_project-slide', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_new.html.twig', [
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    #[Route('/{id}/edit', name: 'project-slide_edit')]
    public function edit(Request $request, ProjectSlide $projectSlide): Response
    {
        $this->setTranslatableEntity($projectSlide);
        $form = $this->createForm(ProjectSlideType::class, $this->createFormData());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->setTranslatableFieldsAndFlushForm($form);
            return $this->redirectToRoute('dashboard_project-slide', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_edit.html.twig', [
            'entity' => $projectSlide,
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    public function createFormData(): array
    {
        return [
            'description' => $this->entityTranslation->getDescription(),
            'orderValue' => $this->entity->getOrderValue(),
            'project' => $this->entity->getProject()
        ];
    }

    #[Route('/{id}/delete', name: 'project-slide_delete')]
    public function delete(ProjectSlide $projectSlide)
    {
        $this->entityManager->remove($projectSlide);
        $this->entityManager->flush();
        return $this->redirectToRoute('dashboard_project-slide', [], Response::HTTP_SEE_OTHER);
    }

    public function setTranslatableEntityFieldsFromForm($form)
    {
        $this->entity->setOrderValue($form['orderValue']->getData());
        $this->entity->setProject($form['project']->getData());
        $this->entityTranslation->setDescription($form['description']->getData());

        $uploadedFile = $form['imageFile']->getData();
        if ($uploadedFile) {
            $newFilename = $this->uploaderHelper->uploadProjectImage($uploadedFile);
            $this->entity->setImageFilename($newFilename);
        }
    }
}