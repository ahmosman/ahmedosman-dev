<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Timeline;
use App\Entity\TimelineCategory;
use App\Repository\TimelineRepository;
use App\Tests\DatabaseDependantWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TimelineControllerTest extends DatabaseDependantWebTestCase
{
    private TimelineRepository $repository;

    /** @test */
    public function newTimelineCanBeCreatedInPl()
    {
        $locale = 'pl';

        $this->client->request('GET', $this->router->generate(
            'timeline-category_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'timeline_category[name]' => 'Edukacja'
        ]);

        $timelineCategoryRepository = $this->entityManager->getRepository(TimelineCategory::class);
        $timelineCategoryRecord = $timelineCategoryRepository->find(1);

        $originalNumObjectsInRepository = count($this->repository->findAll());
        $this->client->request('GET', $this->router->generate(
            'timeline_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);
        $this->client->submitForm('btn-save', [
            'timeline[title]' => 'Tytuł',
            'timeline[subtitle]' => 'Podtytuł',
            'timeline[date][year]' => 2018,
            'timeline[date][month]' => 6,
            'timeline[date][day]' => 1,
            'timeline[dateRange]' => 'czerwiec 2018 - sierpień 2019',
            'timeline[link]' => 'Test link',
            'timeline[timelineCategory]' => $timelineCategoryRecord
        ]);
        self::assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);

        $timelineRecord = $this->repository->find(1);
        $timelineRecordTranslation = $timelineRecord->translate($locale);
        $timelineCategoryTranslation = $timelineRecord->getTimelineCategory()->translate($locale);

        self::assertEquals('Tytuł', $timelineRecordTranslation->getTitle());
        self::assertEquals('Podtytuł', $timelineRecordTranslation->getSubtitle());
        self::assertEquals('2018-06-01', $timelineRecord->getDate()->format('Y-m-d'));
        self::assertEquals('czerwiec 2018 - sierpień 2019', $timelineRecordTranslation->getDateRange());
        self::assertEquals('Test link', $timelineRecord->getLink());
        self::assertEquals('Edukacja', $timelineCategoryTranslation->getName());
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_timeline'));
    }

    /** @test */
    public function timelineCanBeEditedDependingOnLocale()
    {
        $locale = 'pl';

        $timelineCategory = new TimelineCategory();
        $timelineCategoryEn = $timelineCategory->translate('en');
        $timelineCategoryEn->setName('Education');
        $timelineCategoryPl = $timelineCategory->translate('pl');
        $timelineCategoryPl->setName('Edukacja');

        $this->entityManager->persist($timelineCategoryPl);
        $this->entityManager->persist($timelineCategoryEn);
        $this->entityManager->persist($timelineCategory);
        $this->entityManager->flush();

        $timelineCategoryRepository = $this->entityManager->getRepository(TimelineCategory::class);
        $timelineCategoryRecord = $timelineCategoryRepository->find(1);

        $this->client->request('GET', $this->router->generate(
            'timeline_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);
        $this->client->submitForm('btn-save', [
            'timeline[title]' => 'Tytuł1213',
            'timeline[subtitle]' => '123Podtytuł',
            'timeline[date][year]' => 2027,
            'timeline[date][month]' => 6,
            'timeline[date][day]' => 11,
            'timeline[dateRange]' => 'czerwiec 2018 - sierpień 2029',
            'timeline[link]' => 'Test link',
            'timeline[timelineCategory]' => $timelineCategoryRecord
        ]);

        $this->client->request('GET', $this->router->generate('timeline_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update', [
            'timeline[title]' => 'Tytuł',
            'timeline[subtitle]' => 'Podtytuł',
            'timeline[date][year]' => 2018,
            'timeline[date][month]' => 6,
            'timeline[date][day]' => 1,
            'timeline[dateRange]' => 'czerwiec 2018 - sierpień 2019',
        ]);

        $locale = 'en';
        $this->client->request('GET', $this->router->generate('timeline_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));

        $this->client->submitForm('btn-update', [
            'timeline[title]' => 'Title',
            'timeline[subtitle]' => 'Subtitle',
            'timeline[dateRange]' => 'June 2018 - August 2019',
        ]);

        $timelineRecord = $this->repository->find(1);
        $timelinePlTranslation = $timelineRecord->translate('pl');
        $timelineEnTranslation = $timelineRecord->translate('en');
        $timelineCategoryPlRecord = $timelineRecord->getTimelineCategory()->translate('pl');
        $timelineCategoryEnRecord = $timelineRecord->getTimelineCategory()->translate('en');

        self::assertEquals('Tytuł', $timelinePlTranslation->getTitle());
        self::assertEquals('Podtytuł', $timelinePlTranslation->getSubtitle());
        self::assertEquals('czerwiec 2018 - sierpień 2019', $timelinePlTranslation->getDateRange());
        self::assertEquals('Title', $timelineEnTranslation->getTitle());
        self::assertEquals('Subtitle', $timelineEnTranslation->getSubtitle());
        self::assertEquals('June 2018 - August 2019', $timelineEnTranslation->getDateRange());
        self::assertEquals('Test link', $timelineRecord->getLink());
        self::assertEquals('2018-06-01', $timelineRecord->getDate()->format('Y-m-d'));
        self::assertEquals('Edukacja', $timelineCategoryPlRecord->getName());
        self::assertEquals('Education', $timelineCategoryEnRecord->getName());
        self::assertResponseRedirects($this->router->generate('dashboard_timeline', ['_locale' => $locale]));

    }

    /** @test */
    public function timelineCanBeRemoved()
    {
        $locale = 'en';
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', $this->router->generate(
            'timeline_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'timeline[title]' => 'Tytuł',
            'timeline[subtitle]' => 'Podtytuł',
            'timeline[date][year]' => 2018,
            'timeline[date][month]' => 6,
            'timeline[date][day]' => 1,
            'timeline[dateRange]' => 'czerwiec 2018 - sierpień 2019',
            'timeline[link]' => 'Test link'
        ]);
        self::assertSame($originalNumObjectsInRepository + 1,
            count($this->repository->findAll()));

        $this->client->request('GET', $this->router->generate('timeline_delete', ['id' => 1]));
        self::assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);
        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_timeline'));

    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Timeline::class);
    }

}