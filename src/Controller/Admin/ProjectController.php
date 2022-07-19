<?php

namespace App\Controller\Admin;

use App\Controller\Abstract\AbstractTranslatableCrudController;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/project')]
class ProjectController extends AbstractTranslatableCrudController
{
    private string $entityName;
    private UploaderHelper $uploaderHelper;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper)
    {
        parent::__construct($requestStack, $entityManager);
        $this->entityName = 'Project';
        $this->uploaderHelper = $uploaderHelper;
    }

    #[Route('/new', name: 'project_new')]
    public function new(Request $request): Response
    {
        $this->setTranslatableEntity(new Project());

        $form = $this->createForm(ProjectType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);
            return $this->redirectToRoute('dashboard_project', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_new.html.twig', [
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    #[Route('/{id}/edit', name: 'project_edit')]
    public function edit(Request $request, Project $project): Response
    {
        $this->setTranslatableEntity($project);
        $form = $this->createForm(ProjectType::class, $this->createFormData());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->setTranslatableFieldsAndFlushForm($form);
            return $this->redirectToRoute('dashboard_project', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_edit.html.twig', [
            'entity' => $project,
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    public function createFormData(): array
    {
        return [
            'title' => $this->entityTranslation->getTitle(),
            'subtitle' => $this->entityTranslation->getSubtitle(),
            'shortDescription' => $this->entityTranslation->getShortDescription(),
            'description' => $this->entityTranslation->getDescription(),
            'usedTools' => $this->entityTranslation->getUsedTools(),
            'orderValue' => $this->entity->getOrderValue(),
            'githubLink' => $this->entity->getGithublink(),
            'webLink' => $this->entity->getWebLink()
        ];
    }

    #[Route('/{id}/delete', name: 'project_delete')]
    public function delete(Project $project)
    {
        $this->entityManager->remove($project);
        $this->entityManager->flush();
        return $this->redirectToRoute('dashboard_project', [], Response::HTTP_SEE_OTHER);
    }

    public function setTranslatableEntityFieldsFromForm($form)
    {
        $this->entity->setOrderValue($form['orderValue']->getData());
        $this->entity->setGithubLink($form['githubLink']->getData());
        $this->entity->setWebLink($form['webLink']->getData());

        $this->entityTranslation->setTitle($form['title']->getData());
        $this->entityTranslation->setSubtitle($form['subtitle']->getData());
        $this->entityTranslation->setDescription($form['description']->getData());
        $this->entityTranslation->setShortDescription($form['shortDescription']->getData());
        $this->entityTranslation->setUsedTools($form['usedTools']->getData());

        $uploadedFile = $form['imageFile']->getData();
        if ($uploadedFile) {
            $newFilename = $this->uploaderHelper->uploadProjectImage($uploadedFile);
            $this->entity->setImageFilename($newFilename);
        }
    }
}