<?php

namespace App\Controller;

use App\Entity\Paragraph;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'default')]
    public function indexNoLocale(): Response
    {
        return $this->redirectToRoute('main', ['_locale' => 'pl']);
    }

    #[Route('/{_locale<%app.supported_locales%>}/', name: 'main')]
    public function index(): Response
    {

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    #[Route('/{_locale<%app.supported_locales%>}/flush', name: 'flush')]
    public function testFlush(): Response
    {
        $paragraphRepository = $this->entityManager->getRepository(Paragraph::class);
        $paragraphRecord = $paragraphRepository->findOneBy(['textID' => 'about-me']);
        dump($paragraphRecord);
        //        $translationRepository = $this->entityManager->getRepository(Translation::class);
//        $translation = $translationRepository->findTranslations($paragraphRecord);
//        dump($translation);


        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
