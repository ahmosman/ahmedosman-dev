<?php

namespace App\Controller\Admin;

use App\Entity\Heading;
use App\Form\HeadingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/heading')]
class HeadingController extends AbstractTranslatableCrudController
{

    public function setTranslatableEntityFieldsFromForm($form)
    {
        $this->entity->setTextID($form['textID']->getData());
        $this->entityTranslation->setName($form['name']->getData());
    }

    #[Route('/new', name: 'heading_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->setTranslatableEntity(new Heading());

        $form = $this->createForm(HeadingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);

            return $this->redirectToRoute(
                'dashboard_paragraph',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('CrudForm/_new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'heading_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Heading $heading)
    {
        $this->setTranslatableEntity($heading);
        $form = $this->createForm(HeadingType::class, $this->createFormData());
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);

            return $this->redirectToRoute(
                'dashboard_heading',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('CrudForm/_edit.html.twig', [
            'entity' => $heading,
            'form' => $form,
        ]);
    }

    public function createFormData(): array
    {
        return [
            'textID' => $this->entity->getTextID(),
            'name' => $this->entityTranslation->getName(),
        ];
    }

    #[Route('/{id}/delete', name: 'heading_delete')]
    public function delete(Heading $heading)
    {
        $this->entityManager->remove($heading);
        $this->entityManager->flush();

        return $this->redirectToRoute(
            'dashboard_heading',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

}
