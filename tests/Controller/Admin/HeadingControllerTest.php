<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Heading;
use App\Repository\HeadingRepository;
use App\Tests\DatabaseDependantWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HeadingControllerTest extends DatabaseDependantWebTestCase
{
    private HeadingRepository $repository;

    /** @test */
    public function newHeadingCanBeCreatedInPl()
    {
        $locale = 'pl';
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $this->client->request(
            'GET',
            $this->router->generate(
                'heading_new',
                ['_locale' => $locale]
            )
        );
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'heading[textID]' => 'about-me',
            'heading[name]' => 'Kim jestem, dokąd zmierzam'
        ]);
        $headingRecord = $this->repository->findOneBy(['textID' => 'about-me']);
        $headingTranslation = $headingRecord->translate($locale);


        self::assertEquals('about-me', $headingRecord->getTextID());
        self::assertEquals(
            'Kim jestem, dokąd zmierzam',
            $headingTranslation->getName()
        );
        self::assertResponseRedirects(
            $this->router->generate('dashboard_heading')
        );
        self::assertSame(
            $originalNumObjectsInRepository + 1,
            count($this->repository->findAll())
        );
    }

    /** @test */
    public function headingCanBeEditedDependingOnLocale(): void
    {
        $locale = 'pl';
        $this->client->request(
            'GET',
            $this->router->generate(
                'heading_new',
                ['_locale' => $locale]
            )
        );
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'heading[textID]' => 'homepage-1',
            'heading[name]' => 'adsds Cześć, jestem Ahmeadsd',
        ]);

        $headingRecord = $this->repository->findOneBy(['textID' => 'homepage-1']);

        $this->client->request(
            'GET',
            $this->router->generate(
                'heading_edit',
                [
                    '_locale' => $locale,
                    'id' => $headingRecord->getId()
                ]
            )
        );
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update', [
            'heading[name]' => 'Cześć, jestem Ahmed',
        ]);

        $locale = 'en';
        $this->client->request(
            'GET',
            $this->router->generate(
                'heading_edit',
                [
                    '_locale' => $locale,
                    'id' => $headingRecord->getId()
                ]
            )
        );
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update', [
            'heading[name]' => 'Hello, my name is Ahmed',
        ]);

        $headingRecord = $this->repository->findOneBy(['textID' => 'homepage-1']
        );
        $headingPl = $headingRecord->translate('pl');
        $headingEn = $headingRecord->translate('en');

        self::assertSame('homepage-1', $headingRecord->getTextID());
        self::assertSame('Cześć, jestem Ahmed', $headingPl->getName());
        self::assertSame('Hello, my name is Ahmed', $headingEn->getName());
        self::assertResponseRedirects(
            $this->router->generate('dashboard_heading', ['_locale' => 'en'])
        );
    }

    /** @test */
    public function headingCanBeRemoved()
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $locale = 'pl';
        $this->client->request(
            'GET',
            $this->router->generate(
                'heading_new',
                ['_locale' => $locale]
            )
        );
        self::assertResponseStatusCodeSame(200);
        $this->client->submitForm('btn-save', [
            'heading[textID]' => 'contact',
            'heading[name]' => 'kontakt',
        ]);

        $headingRecord = $this->repository->findOneBy(['textID' => 'contact']);
        self::assertSame(
            $originalNumObjectsInRepository + 1,
            count($this->repository->findAll())
        );

        $this->client->request(
            'GET',
            $this->router->generate(
                'heading_delete',
                ['id' => $headingRecord->getId()]
            )
        );
        self::assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);

        self::assertSame(
            $originalNumObjectsInRepository,
            count($this->repository->findAll())
        );
        self::assertResponseRedirects(
            $this->router->generate('dashboard_heading')
        );
    }

    /** @test */
    public function headingCanBeDisplayedProperlyDuringEditionInEn()
    {
        $locale = 'en';
        $this->client->request(
            'GET',
            $this->router->generate(
                'heading_new',
                ['_locale' => $locale]
            )
        );
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'heading[textID]' => 'about-me',
            'heading[name]' => 'Who I am, where I am going'
        ]);

        $crawler = $this->client->request(
            'GET',
            $this->router->generate(
                'heading_edit',
                ['_locale' => $locale, 'id' => 1]
            )
        );


        self::assertResponseStatusCodeSame(200);
        self::assertEquals(
            'about-me',
            $crawler->filter('#heading_textID')->attr('value')
        );
        self::assertEquals(
            'Who I am, where I am going',
            $crawler->filter('#heading_name')->attr('value')
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Heading::class);
    }

}
