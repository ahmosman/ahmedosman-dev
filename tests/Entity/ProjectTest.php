<?php

namespace App\Tests\Entity;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Tests\DatabaseDependantWebTestCase;

class ProjectTest extends DatabaseDependantWebTestCase
{
    private ProjectRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Project::class);
    }

    /** @test */
    public function projectCanBeAddedToDatabaseInBothLanguages()
    {
        $project = new Project();

        $project->setImageFilename('test-image.jpg');
        $project->setOrderValue(1);

        $projectPl = $project->translate('pl');
        $projectEn = $project->translate('en');

        $projectPl->setTitle('Polbahasa');
        $projectPl->setSubtitle('Słownik indonezyjsko-polski');
        $projectPl->setDescription('Testowy opis słownika');
        $projectPl->setShortDescription('Krótki opis słownika');

        $projectEn->setTitle('Polbahasa');
        $projectEn->setSubtitle('Indonesian-Polish dictionary');
        $projectEn->setDescription('Test dictionary description');
        $projectEn->setShortDescription('Short dictionary description');

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $projectRecord = $this->repository->find(1);
        $projectPlRecord = $projectRecord->translate('pl');
        $projectEnRecord = $projectRecord->translate('en');


        self::assertEquals('test-image.jpg', $projectRecord->getImageFilename());
        self::assertEquals(1, $projectRecord->getOrderValue());
        self::assertEquals('Polbahasa', $projectPlRecord->getTitle());
        self::assertEquals('Słownik indonezyjsko-polski', $projectPlRecord->getSubtitle());
        self::assertEquals('Testowy opis słownika', $projectPlRecord->getDescription());
        self::assertEquals('Krótki opis słownika', $projectPlRecord->getShortDescription());
        self::assertEquals('Polbahasa', $projectEnRecord->getTitle());
        self::assertEquals('Indonesian-Polish dictionary', $projectEnRecord->getSubtitle());
        self::assertEquals('Test dictionary description', $projectEnRecord->getDescription());
        self::assertEquals('Short dictionary description', $projectEnRecord->getShortDescription());
    }
}
