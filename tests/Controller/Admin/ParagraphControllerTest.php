<?php

namespace App\Test\Controller;

use App\Entity\Paragraph;
use App\Repository\ParagraphRepository;
use App\Tests\DatabaseDependantWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParagraphControllerTest extends DatabaseDependantWebTestCase
{
    private ParagraphRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Paragraph::class);
    }

    /** @test */
    public function newParagraphCanBeCreatedInPl(): void
    {
        $locale = 'pl';
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', $this->router->generate('paragraph_new',['_locale' => $locale]));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'paragraph[textID]' => 'about-me',
            'paragraph[title]' => 'O mnie',
            'paragraph[description]' => 'Po zakończeniu gimnazjum zacząłem zastanawiać się co mnie satysfakcjonuje w życiu.',
        ]);

        $paragraphRecord = $this->repository->findOneBy(['textID' => 'about-me']);
        $paragraphTranslation = $paragraphRecord->translate($locale);

        self::assertEquals('about-me', $paragraphRecord->getTextID());
        self::assertEquals('O mnie', $paragraphTranslation->getTitle());
        self::assertEquals('Po zakończeniu gimnazjum zacząłem zastanawiać się co mnie satysfakcjonuje w życiu.', $paragraphTranslation->getDescription());
        self::assertResponseRedirects($this->router->generate('dashboard_paragraph'));
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    /** @test */
    public function newParagraphCanBeCreatedInEn(): void
    {
        $locale = 'en';
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', $this->router->generate('paragraph_new',['_locale' => $locale]));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'paragraph[textID]' => 'about-me',
            'paragraph[title]' => 'About me',
            'paragraph[description]' => 'After ending middle school I started to wonder.',
        ]);

        $paragraphRecord = $this->repository->findOneBy(['textID' => 'about-me']);
        $paragraphTranslation = $paragraphRecord->translate($locale);

        self::assertEquals('about-me', $paragraphRecord->getTextID());
        self::assertEquals('About me', $paragraphTranslation->getTitle());
        self::assertEquals('After ending middle school I started to wonder.', $paragraphTranslation->getDescription());
        self::assertResponseRedirects($this->router->generate('dashboard_paragraph'));
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    /** @test */
    public function paragraphCanBeEditedDependingOnLocale(): void
    {
        $locale = 'pl';
        $this->client->request('GET', $this->router->generate('paragraph_new',['_locale' => $locale]));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'paragraph[textID]' => 'about-me',
            'paragraph[title]' => 'New title',
            'paragraph[description]' => 'New description',
        ]);

        $paragraphRecord = $this->repository->findOneBy(['textID' => 'about-me']);

        $this->client->request('GET', $this->router->generate('paragraph_edit', ['_locale' => $locale, 'id' => $paragraphRecord->getId()]));
        $this->client->submitForm('Update', [
            'paragraph[title]' => 'O mnie',
            'paragraph[description]' => 'Po zakończeniu gimnazjum zacząłem zastanawiać się co mnie satysfakcjonuje w życiu.',
        ]);

        $locale = 'en';
        $this->client->request('GET', $this->router->generate('paragraph_edit', ['_locale' => $locale, 'id' => $paragraphRecord->getId()]));
        $this->client->submitForm('Update', [
            'paragraph[title]' => 'About me',
            'paragraph[description]' => 'After ending middle school I started to wonder.',
        ]);

        $paragraphRecord = $this->repository->findOneBy(['textID' => 'about-me']);
        $paragraphPl = $paragraphRecord->translate('pl');
        $paragraphEn = $paragraphRecord->translate('en');

        self::assertSame('About me', $paragraphEn->getTitle());
        self::assertSame('After ending middle school I started to wonder.', $paragraphEn->getDescription());
        self::assertSame('O mnie', $paragraphPl->getTitle());
        self::assertSame('Po zakończeniu gimnazjum zacząłem zastanawiać się co mnie satysfakcjonuje w życiu.', $paragraphPl->getDescription());
        self::assertResponseRedirects($this->router->generate('dashboard_paragraph',['_locale' => 'en']));
    }

    /** @test */
    public function paragraphCanBeRemoved(): void
    {

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $locale = 'pl';
        $this->client->request('GET', $this->router->generate('paragraph_new',['_locale' => $locale]));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'paragraph[textID]' => 'about-me',
            'paragraph[title]' => 'New title',
            'paragraph[description]' => 'New description',
        ]);

        $paragraphRecord = $this->repository->findOneBy(['textID' => 'about-me']);


        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        $this->client->request('GET', $this->router->generate('paragraph_delete',['id' => $paragraphRecord->getId()]));


        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_paragraph'));
    }
}
