<?php

namespace App\Controller\Admin;

use App\Entity\Heading;
use App\Form\HeadingType;
use App\Repository\HeadingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/heading')]
class HeadingController extends AbstractTranslatableCrudController
{
    #[Route('/new', name: 'heading_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $heading = new Heading();

        $form = $this->createForm(HeadingType::class, $heading);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $headingTranslation = $heading->translate($this->locale);
            $headingTranslation->setName($form['name']->getData());

            $heading->mergeNewTranslations();
            $this->entityManager->persist($heading);
            $this->entityManager->flush();

            return $this->redirectToRoute('dashboard_paragraph', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_new.html.twig', [
            'form' => $form,
        ]);
    }
}
