<?php

namespace App\Controller;

use App\Entity\Heading;
use App\Service\NonExistingTextIDException;
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
     * @throws NonExistingTextIDException
     */
    #[Route('/{_locale<%app.supported_locales%>}/', name: 'homepage')]
    public function index(): Response
    {
        $headings = $this->contentGenerator->generateContentArrayForTextID(Heading::class, ['home_1','home_2','home_3','home_portfolio','home_me','home_contact'],$this->locale);
        return $this->render('main/home.html.twig', [
            'headings' => $headings,
        ]);
    }

}
