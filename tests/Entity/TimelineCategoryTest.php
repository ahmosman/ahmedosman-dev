<?php

namespace App\Tests\Entity;

use App\Entity\TimelineCategory;
use App\Repository\TimelineCategoryRepository;
use App\Tests\DatabaseDependantWebTestCase;

class TimelineCategoryTest extends DatabaseDependantWebTestCase
{
    private TimelineCategoryRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(TimelineCategory::class);
    }

    /** @test */
    public function timelineCanBeAddedToDatabaseInBothLanguages()
    {
        $timelineCategory = new TimelineCategory();
        $timelineCategoryEn = $timelineCategory->translate('en');
        $timelineCategoryEn->setName('Education');
        $timelineCategoryPl = $timelineCategory->translate('pl');
        $timelineCategoryPl->setName('Edukacja');

        $this->entityManager->persist($timelineCategory);
        $this->entityManager->flush();
        $timelineCategoryRecord = $this->repository->find(1);
        $timelineCategoryEnRecord = $timelineCategoryRecord->translate('en');
        $timelineCategoryPlRecord = $timelineCategoryRecord->translate('pl');

        self::assertEquals('Education', $timelineCategoryEnRecord->getName());
        self::assertEquals('Edukacja', $timelineCategoryPlRecord->getName());
    }
}