<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\ProjectSlideController;
use App\Entity\ProjectSlide;
use App\Repository\ProjectSlideRepository;
use App\Tests\DatabaseDependantWebTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ProjectSlideControllerTest extends DatabaseDependantWebTestCase
{
    private ProjectSlideRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(ProjectSlide::class);
    }

    /** @test */
    public function newProjectSlideCanBeCreatedInPl()
    {
        $locale = 'pl';
        $originalNumObjectsInRepository = count($this->repository->findAll());


        $this->client->request('GET', $this->router->generate(
            'project-slide_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save',[
           'project_slide[description]' => 'Testowy opis',
           'project_slide[orderValue]' => 2,
        ]);

        $projectSlideRecord = $this->repository->find(1);
        $projectSlideTranslation = $projectSlideRecord->translate($locale);


        self::assertEquals('Testowy opis', $projectSlideTranslation->getDescription());
        self::assertEquals(2, $projectSlideRecord->getOrderValue());
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_project-slide'));
    }

    /** @test */
    public function projectSlideCanBeEditedDependingOnLocale()
    {
        $locale = 'pl';

        $this->client->request('GET', $this->router->generate(
            'project-slide_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save',[
            'project_slide[description]' => 'Testowy opis211',
            'project_slide[orderValue]' => 4,
        ]);

        $this->client->request('GET', $this->router->generate('project-slide_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update',[
            'project_slide[description]' => 'Testowy opis',
            'project_slide[orderValue]' => 2,
        ]);

        $locale = 'en';
        $this->client->request('GET', $this->router->generate('project-slide_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update',[
            'project_slide[description]' => 'Test description'
        ]);

        $projectSlideRecord = $this->repository->find(1);

        $projectSlidePl = $projectSlideRecord->translate('pl');
        $projectSlideEn = $projectSlideRecord->translate('en');


        self::assertEquals(2, $projectSlideRecord->getOrderValue());
        self::assertEquals('Testowy opis', $projectSlidePl->getDescription());
        self::assertEquals('Test description', $projectSlideEn->getDescription());
        self::assertResponseRedirects($this->router->generate('dashboard_project-slide', ['_locale' => $locale]));

    }

    /** @test */
    public function projectSlideCanBeRemoved()
    {
        $locale = 'pl';
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', $this->router->generate(
            'project-slide_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save',[
            'project_slide[description]' => 'Testowy opis',
            'project_slide[orderValue]' => 2,
        ]);

        self::assertSame($originalNumObjectsInRepository + 1,
            count($this->repository->findAll()));

        $this->client->request('GET', $this->router->generate('project-slide_delete', ['id' => 1]));


        self::assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);
        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_project-slide'));

    }
}
