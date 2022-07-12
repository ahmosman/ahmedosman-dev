<?php

namespace App\Controller;

use App\Entity\Heading;
use App\Service\TranslatableContentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractTranslatablePageContentController
{

    #[Route('/')]
    public function indexNoLocale(): Response
    {
        return $this->redirectToRoute('homepage', ['_locale' => 'pl']);
    }

    /**
     * @throws TranslatableContentException
     */
    #[Route('/{_locale<%app.supported_locales%>}/', name: 'homepage')]
    public function index(): Response
    {
        $headings = $this->contentGenerator->generateContentTextIDArray(Heading::class,$this->locale);
        return $this->render('main/home.html.twig', [
            'headings' => $headings,
        ]);
    }

}
