<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Tool;
use App\Repository\ToolRepository;
use App\Tests\DatabaseDependantWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ToolControllerTest extends DatabaseDependantWebTestCase
{
    private ToolRepository $repository;
    private string $locale;

    /** @test */
    public function newToolCanBeCreated()
    {

        $originalNumObjectsInRepository = count($this->repository->findAll());
        $this->client->request('GET', $this->router->generate(
            'tool_new', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'tool[name]' => 'Github',
            'tool[imgSrc]' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg'
        ]);

        $toolRecord = $this->repository->find(1);


        self::assertEquals('Github', $toolRecord->getName());
        self::assertEquals('https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg', $toolRecord->getImgSrc());
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_tool'));
    }

    /** @test */
    public function toolCanBeEdited()
    {
        $this->client->request('GET', $this->router->generate(
            'tool_new', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'tool[name]' => 'Github',
            'tool[imgSrc]' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg'
        ]);

        $this->client->request('GET', $this->router->generate('tool_edit', [
            '_locale' => $this->locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update', [
            'tool[name]' => 'Symfony',
            'tool[imgSrc]' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/symfony/symfony-original.svg'
        ]);

        $toolRecord = $this->repository->find(1);

        self::assertEquals('Symfony', $toolRecord->getName());
        self::assertEquals('https://cdn.jsdelivr.net/gh/devicons/devicon/icons/symfony/symfony-original.svg', $toolRecord->getImgSrc());
        self::assertResponseRedirects($this->router->generate('dashboard_tool',['_locale' => $this->locale]));
    }

    /** @test */
    public function toolCanBeRemoved(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $this->client->request('GET', $this->router->generate(
            'tool_new', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'tool[name]' => 'Github',
            'tool[imgSrc]' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg'
        ]);

        self::assertSame($originalNumObjectsInRepository + 1,
            count($this->repository->findAll()));

        $this->client->request('GET', $this->router->generate('tool_delete',['id' => 1]));
        self::assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);
        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_tool'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Tool::class);
        $this->locale = 'pl';
    }
}
