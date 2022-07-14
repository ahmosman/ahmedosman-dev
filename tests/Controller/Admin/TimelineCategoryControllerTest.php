<?php

namespace App\Tests\Controller\Admin;

use App\Entity\TimelineCategory;
use App\Repository\TimelineCategoryRepository;
use App\Tests\DatabaseDependantWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TimelineCategoryControllerTest extends DatabaseDependantWebTestCase
{
    private TimelineCategoryRepository $repository;

    /** @test */
    public function newTimelineCategoryCanBeCreatedInEn()
    {
        $locale = 'en';
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $this->client->request('GET', $this->router->generate(
            'timeline-category_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'timeline_category[name]' => 'Education'
        ]);


        $timelineCategoryRecord = $this->repository->find(1);
        $timelineCategoryTranslation = $timelineCategoryRecord->translate($locale);


        self::assertEquals('Education', $timelineCategoryTranslation->getName());
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_timeline-category'));
    }

    /** @test */
    public function timelineCategoryCanBeEditedDependingOnLocale()
    {
        $locale = 'pl';
        $this->client->request('GET', $this->router->generate(
            'timeline-category_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'timeline_category[name]' => 'Edukować'
        ]);

        $this->client->request('GET', $this->router->generate('timeline-category_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update', [
            'timeline_category[name]' => 'Edukacja'
        ]);

        $locale = 'en';
        $this->client->request('GET', $this->router->generate('timeline-category_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));

        $this->client->submitForm('btn-update', [
            'timeline_category[name]' => 'Education'
        ]);

        $timelineCategoryRecord = $this->repository->find(1);
        $timelineCategoryPlTranslation = $timelineCategoryRecord->translate('pl');
        $timelineCategoryEnTranslation = $timelineCategoryRecord->translate('en');


        self::assertEquals('Edukacja', $timelineCategoryPlTranslation->getName());
        self::assertEquals('Education', $timelineCategoryEnTranslation->getName());
        self::assertResponseRedirects($this->router->generate('dashboard_timeline-category',['_locale' => $locale]));

    }

    /** @test */
    public function timelineCategoryCanBeRemoved()
    {
        $locale = 'pl';
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', $this->router->generate(
            'timeline-category_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'timeline_category[name]' => 'Edukacja'
        ]);

        self::assertSame($originalNumObjectsInRepository + 1,
            count($this->repository->findAll()));

        $this->client->request('GET', $this->router->generate('timeline-category_delete',['id' => 1]));
        self::assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);
        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_timeline-category'));

    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(TimelineCategory::class);
    }
}
