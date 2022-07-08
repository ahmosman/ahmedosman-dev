<?php

namespace App\Controller\Admin;

use App\Entity\Paragraph;
use App\Form\ParagraphType;
use App\Repository\ParagraphRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/paragraph')]
class ParagraphController extends AbstractController
{
    private $locale;
    private EntityManagerInterface $entityManager;
    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->locale = $requestStack->getCurrentRequest()->getLocale();
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_paragraph_index', methods: ['GET'])]
    public function index(ParagraphRepository $paragraphRepository): Response
    {
        return $this->render('paragraph/index.html.twig', [
            'paragraphs' => $paragraphRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'paragraph_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParagraphRepository $paragraphRepository): Response
    {
        $paragraph = new Paragraph();

        $form = $this->createForm(ParagraphType::class, $paragraph);

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

        return $this->renderForm('paragraph/new.html.twig', [
            'paragraph' => $paragraph,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'paragraph_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paragraph $paragraph, ParagraphRepository $paragraphRepository): Response
    {
        $form = $this->createForm(ParagraphType::class, $paragraph);
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

        return $this->renderForm('paragraph/edit.html.twig', [
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
