<?php

namespace App\Tests\Entity;

use App\Entity\Timeline;
use App\Entity\TimelineCategory;
use App\Repository\TimelineRepository;
use App\Tests\DatabaseDependantWebTestCase;

class TimelineTest extends DatabaseDependantWebTestCase
{
    private TimelineRepository $repository;

    /** @test */
    public function timelineCanBeAddedInBothLanguages()
    {
        $timelineCategory = new TimelineCategory();
        $timelineCategoryEn = $timelineCategory->translate('en');
        $timelineCategoryEn->setName('Education');
        $timelineCategoryPl = $timelineCategory->translate('pl');
        $timelineCategoryPl->setName('Edukacja');

        $timeline = new Timeline();
        $timeline->setDate(\DateTime::createFromFormat('Y-m-d', '2018-06-01'));
        $timeline->setLink('test link');
        $timeline->setTimelineCategory($timelineCategory);

        $timelinePl = $timeline->translate('pl');
        $timelinePl->setDateRange('czerwiec 2018 - sierpień 2019');
        $timelinePl->setTitle('Tytuł');
        $timelinePl->setSubtitle('Podtytuł');

        $timelineEn = $timeline->translate('en');
        $timelineEn->setDateRange('June 2018 - August 2019');
        $timelineEn->setTitle('Test title');
        $timelineEn->setSubtitle('Test subtitle');

        $this->entityManager->persist($timelineCategory);
        $this->entityManager->persist($timeline);
        $this->entityManager->flush();


        $timelineRecord = $this->repository->find(1);
        $timelineEnRecord = $timelineRecord->translate('en');
        $timelinePlRecord = $timelineRecord->translate('pl');
        $timelineCategoryPlRecord = $timelineRecord->getTimelineCategory()->translate('pl');
        $timelineCategoryEnRecord = $timelineRecord->getTimelineCategory()->translate('en');

        self::assertEquals('2018-06-01', $timelineRecord->getDate()->format('Y-m-d'));
        self::assertEquals('test link', $timelineRecord->getLink());
        self::assertEquals('Tytuł', $timelinePlRecord->getTitle());
        self::assertEquals('Podtytuł', $timelinePlRecord->getSubtitle());
        self::assertEquals('czerwiec 2018 - sierpień 2019', $timelinePlRecord->getDateRange());
        self::assertEquals('Test title', $timelineEnRecord->getTitle());
        self::assertEquals('Test subtitle', $timelineEnRecord->getSubtitle());
        self::assertEquals('June 2018 - August 2019', $timelineEnRecord->getDateRange());
        self::assertEquals('Edukacja', $timelineCategoryPlRecord->getName());
        self::assertEquals('Education', $timelineCategoryEnRecord->getName());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Timeline::class);
    }

}
