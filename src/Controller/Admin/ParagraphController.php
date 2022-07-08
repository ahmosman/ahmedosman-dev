<?php

namespace App\Controller\Admin;

use App\Entity\Paragraph;
use App\Form\ParagraphType;
use App\Repository\ParagraphRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/paragraph')]
class ParagraphController extends AbstractTranslatableCrudController
{

    #[Route('/new', name: 'paragraph_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $paragraph = new Paragraph();
        $form = $this->createForm(ParagraphType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paragraph->setTextID($form['textID']->getData());
            $paragraphTranslation = $paragraph->translate($this->locale);
            $paragraphTranslation->setTitle($form['title']->getData());
            $paragraphTranslation->setDescription($form['description']->getData());

            $paragraph->mergeNewTranslations();
            $this->entityManager->persist($paragraph);
            $this->entityManager->flush();

            return $this->redirectToRoute('dashboard_paragraph', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'paragraph_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paragraph $paragraph, ParagraphRepository $paragraphRepository): Response
    {
        $translation = $paragraph->translate($this->locale);
        $formData = [];
        $formData['textID'] = $paragraph->getTextID();
        $formData['title'] = $translation->getTitle();
        $formData['description'] = $translation->getDescription();
        $form = $this->createForm(ParagraphType::class, $formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $paragraphTranslation = $paragraph->translate($this->locale);
            $paragraphTranslation->setTitle($form['title']->getData());
            $paragraphTranslation->setDescription($form['description']->getData());

            $paragraph->mergeNewTranslations();
            $this->entityManager->persist($paragraph);
            $this->entityManager->flush();

            return $this->redirectToRoute('dashboard_paragraph', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_edit.html.twig', [
            'paragraph' => $paragraph,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'paragraph_delete')]
    public function delete(Request $request, Paragraph $paragraph, ParagraphRepository $paragraphRepository): Response
    {
        $paragraphRepository->remove($paragraph, true);

        return $this->redirectToRoute('dashboard_paragraph', [], Response::HTTP_SEE_OTHER);
    }
}
