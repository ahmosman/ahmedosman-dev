<?php

namespace App\Tests\Entity;

use App\Entity\ProjectSlide;
use App\Repository\ProjectSlideRepository;
use App\Tests\DatabaseDependantWebTestCase;

class ProjectSlideTest extends DatabaseDependantWebTestCase
{
    private ProjectSlideRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(ProjectSlide::class);
    }

   /** @test */
    public function projectSlideCanBeAddedToDatabaseInBothLanguages()
    {
        $projectSlide = new ProjectSlide();

        $projectSlide->setOrderValue(2);
        $projectSlide->setImageFilename('test-image.jpg');

        $projectSlideEn = $projectSlide->translate('en');
        $projectSlidePl = $projectSlide->translate('pl');

        $projectSlideEn->setDescription('Test description');
        $projectSlidePl->setDescription('Testowy opis');

        $this->entityManager->persist($projectSlide);
        $this->entityManager->flush();

        $projectSlideRecord = $this->repository->find(1);
        $projectSlideEnRecord = $projectSlideRecord->translate('en');
        $projectSlidePlRecord = $projectSlideRecord->translate('pl');


        self::assertEquals(2, $projectSlideRecord->getOrderValue());
        self::assertEquals('test-image.jpg', $projectSlideRecord->getImageFilename());
        self::assertEquals('Test description', $projectSlideEnRecord->getDescription());
        self::assertEquals('Testowy opis', $projectSlidePlRecord->getDescription());

    }


}