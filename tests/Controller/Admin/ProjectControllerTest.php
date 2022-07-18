<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Tests\DatabaseDependantWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends DatabaseDependantWebTestCase
{
    private ProjectRepository $repository;

    /** @test */
    public function newProjectCanBeCreatedInPl()
    {
        $locale = 'pl';
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', $this->router->generate(
            'project_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'project[title]' => 'Polbahasa',
            'project[subtitle]' => 'Słownik indonezyjsko-polski',
            'project[description]' => 'Testowy opis słownika',
            'project[orderValue]' => 3
        ]);

        $projectRecord = $this->repository->find(1);
        $projectTranslation = $projectRecord->translate($locale);


        self::assertEquals('Polbahasa', $projectTranslation->getTitle());
        self::assertEquals('Słownik indonezyjsko-polski', $projectTranslation->getSubtitle());
        self::assertEquals('Testowy opis słownika', $projectTranslation->getDescription());
        self::assertEquals(3, $projectRecord->getOrderValue());
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_project'));
    }

    /** @test */
    public function projectCanBeEditedDependingOnLocale()
    {
        $locale = 'pl';

        $this->client->request('GET', $this->router->generate(
            'project_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'project[title]' => 'Polbahadasa',
            'project[subtitle]' => 'Słowadsnik indonezyjsko-polski',
            'project[description]' => '123Testowy opis 1słownika',
            'project[orderValue]' => 3
        ]);

        $this->client->request('GET', $this->router->generate('project_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update', [
            'project[title]' => 'Polbahasa',
            'project[subtitle]' => 'Słownik indonezyjsko-polski',
            'project[description]' => 'Testowy opis słownika',
            'project[orderValue]' => 3
        ]);

        $locale = 'en';
        $this->client->request('GET', $this->router->generate('project_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update', [
            'project[title]' => 'Polbahasa',
            'project[subtitle]' => 'Indonesian-Polish dictionary',
            'project[description]' => 'Test dictionary description',
            'project[orderValue]' => 3
        ]);

        $projectRecord = $this->repository->find(1);

        $projectPl = $projectRecord->translate('pl');
        $projectEn = $projectRecord->translate('en');


        self::assertEquals(3, $projectRecord->getOrderValue());
        self::assertEquals('Polbahasa', $projectPl->getTitle());
        self::assertEquals('Słownik indonezyjsko-polski', $projectPl->getSubtitle());
        self::assertEquals('Testowy opis słownika', $projectPl->getDescription());
        self::assertEquals('Polbahasa', $projectEn->getTitle());
        self::assertEquals('Indonesian-Polish dictionary', $projectEn->getSubtitle());
        self::assertEquals('Test dictionary description', $projectEn->getDescription());
        self::assertResponseRedirects($this->router->generate('dashboard_project', ['_locale' => $locale]));

    }

    /** @test */
    public function projectCanBeRemoved()
    {
        $locale = 'pl';
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', $this->router->generate(
            'project_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'project[title]' => 'Polbahasa',
            'project[subtitle]' => 'Słownik indonezyjsko-polski',
            'project[description]' => 'Testowy opis słownika',
            'project[orderValue]' => 3
        ]);

        self::assertSame($originalNumObjectsInRepository + 1,
            count($this->repository->findAll()));

        $this->client->request('GET', $this->router->generate('project_delete', ['id' => 1]));

        self::assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);
        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_project'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Project::class);
    }

}
