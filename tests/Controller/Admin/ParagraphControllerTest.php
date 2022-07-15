<?php

namespace App\Test\Controller\Admin;

use App\Entity\Paragraph;
use App\Repository\ParagraphRepository;
use App\Tests\DatabaseDependantWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ParagraphControllerTest extends DatabaseDependantWebTestCase
{
    private ParagraphRepository $repository;

    /** @test */
    public function newParagraphCanBeCreatedInPl(): void
    {
        $locale = 'pl';
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', $this->router->generate(
            'paragraph_new',
            ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'paragraph[textID]' => 'about-me',
            'paragraph[title]' => 'O mnie',
            'paragraph[description]' => 'Po zakończeniu gimnazjum zacząłem zastanawiać się co mnie satysfakcjonuje w życiu.'
        ]);

        $paragraphRecord = $this->repository->findOneBy(['textID' => 'about-me']);
        $paragraphTranslation = $paragraphRecord->translate($locale);

        self::assertEquals('about-me', $paragraphRecord->getTextID());
        self::assertEquals('O mnie', $paragraphTranslation->getTitle());
        self::assertEquals(
            'Po zakończeniu gimnazjum zacząłem zastanawiać się co mnie satysfakcjonuje w życiu.',
            $paragraphTranslation->getDescription());
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_paragraph'));
    }

    /** @test */
    public function paragraphCanBeEditedDependingOnLocale(): void
    {
        $locale = 'pl';
        $this->client->request('GET', $this->router->generate('paragraph_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'paragraph[textID]' => 'about-me',
            'paragraph[title]' => 'New title',
            'paragraph[description]' => 'New description',
        ]);

        $this->client->request('GET', $this->router->generate('paragraph_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update', [
            'paragraph[title]' => 'O mnie',
            'paragraph[description]' => 'Po zakończeniu gimnazjum zacząłem zastanawiać się co mnie satysfakcjonuje w życiu.',
        ]);

        $locale = 'en';
        $this->client->request('GET', $this->router->generate('paragraph_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);


        $this->client->submitForm('btn-update', [
            'paragraph[title]' => 'About me',
            'paragraph[description]' => 'After ending middle school I started to wonder.',
        ]);

        $paragraphRecord = $this->repository->findOneBy(['textID' => 'about-me']);
        $paragraphPl = $paragraphRecord->translate('pl');
        $paragraphEn = $paragraphRecord->translate('en');

        self::assertSame('about-me', $paragraphRecord->getTextID());
        self::assertSame('About me', $paragraphEn->getTitle());
        self::assertSame(
            'After ending middle school I started to wonder.',
            $paragraphEn->getDescription());
        self::assertSame('O mnie', $paragraphPl->getTitle());
        self::assertSame(
            'Po zakończeniu gimnazjum zacząłem zastanawiać się co mnie satysfakcjonuje w życiu.',
            $paragraphPl->getDescription());
        self::assertResponseRedirects($this->router->generate('dashboard_paragraph', ['_locale' => 'en']));
    }

    /** @test */
    public function paragraphCanBeRemoved(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $locale = 'pl';
        $this->client->request('GET',
            $this->router->generate('paragraph_new',
                ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'paragraph[textID]' => 'about-me',
            'paragraph[title]' => 'New title',
            'paragraph[description]' => 'New description',
        ]);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', $this->router->generate('paragraph_delete', ['id' => 1]));


        self::assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);
        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_paragraph'));
    }

    /** @test */
    public function paragraphIsDisplayedProperlyDuringEditionInEn()
    {
        $locale = 'en';

        $this->client->request(
            'GET',
            $this->router->generate(
                'paragraph_new',
                ['_locale' => $locale]
            )
        );
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'paragraph[textID]' => 'about-me',
            'paragraph[title]' => 'About me',
            'paragraph[description]' => 'After ending middle school I started to wonder.'
        ]);

        $crawler = $this->client->request(
            'GET',
            $this->router->generate(
                'paragraph_edit',
                ['_locale' => $locale, 'id' => 1]
            )
        );
        self::assertResponseStatusCodeSame(200);


        self::assertEquals(
            'about-me',
            $crawler->filter('#paragraph_textID')->attr('value')
        );
        self::assertEquals(
            'About me',
            $crawler->filter('#paragraph_title')->attr('value')
        );
        self::assertEquals(
            'After ending middle school I started to wonder.',
            $crawler->filter('#paragraph_description')->text()
        );
    }


    /** @test */
    public function newEntityNameIsDisplayedCorrectlyDuringCreatingNew()
    {
        $locale = 'pl';
        $crawler = $this->client->request('GET',
            $this->router->generate('paragraph_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);
        self::assertEquals('Create new Paragraph', $crawler->filter('.edit__heading')->text());
    }

    /** @test */
    public function editEntityNameIsDisplayedCorrectlyDuringEdition()
    {
        $locale = 'pl';
        $this->client->request('GET',
            $this->router->generate('paragraph_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'paragraph[textID]' => 'about-me',
            'paragraph[title]' => 'New title',
            'paragraph[description]' => 'New description',
        ]);


        $crawler = $this->client->request(
            'GET',
            $this->router->generate('paragraph_edit', ['_locale' => $locale, 'id' => 1]));

        self::assertResponseStatusCodeSame(200);
        self::assertEquals('Edit Paragraph', $crawler->filter('.edit__heading')->text());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Paragraph::class);
    }
}
