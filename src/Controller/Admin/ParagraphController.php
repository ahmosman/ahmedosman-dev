<?php

namespace App\Controller\Admin;

use App\Controller\Abstract\AbstractTranslatableCrudController;
use App\Entity\Paragraph;
use App\Form\ParagraphType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/paragraph')]
class ParagraphController extends AbstractTranslatableCrudController
{
    private string $entityName;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        parent::__construct($requestStack, $entityManager);
        $this->entityName = 'Paragraph';
    }

    public function setTranslatableEntityFieldsFromForm($form)
    {
        $this->entity->setTextID($form['textID']->getData());
        $this->entityTranslation->setTitle($form['title']->getData());
        $this->entityTranslation->setDescription(
            $form['description']->getData()
        );
    }

    #[Route('/new', name: 'paragraph_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->setTranslatableEntity(new Paragraph());

        $form = $this->createForm(ParagraphType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);

            return $this->redirectToRoute('dashboard_paragraph', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_new.html.twig', [
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    #[Route('/{id}/edit', name: 'paragraph_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paragraph $paragraph): Response
    {
        $this->setTranslatableEntity($paragraph);

        $form = $this->createForm(ParagraphType::class, $this->createFormData());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);

            return $this->redirectToRoute('dashboard_paragraph', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_edit.html.twig', [
            'form' => $form,
            'entity' => $paragraph,
            'entity_name' => $this->entityName
        ]);
    }

    public function createFormData(): array
    {
        return [
            'textID' => $this->entity->getTextID(),
            'title' => $this->entityTranslation->getTitle(),
            'description' => $this->entityTranslation->getDescription(),
        ];
    }

    #[Route('/{id}/delete', name: 'paragraph_delete')]
    public function delete(Paragraph $paragraph): Response
    {
        $this->entityManager->remove($paragraph);
        $this->entityManager->flush();

        return $this->redirectToRoute('dashboard_paragraph', [], Response::HTTP_SEE_OTHER);
    }

}
