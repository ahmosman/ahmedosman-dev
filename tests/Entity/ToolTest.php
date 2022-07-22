<?php

namespace App\Tests\Entity;

use App\Entity\Tool;
use App\Repository\ToolRepository;
use App\Tests\DatabaseDependantWebTestCase;


class ToolTest extends DatabaseDependantWebTestCase
{
    private ToolRepository $repository;

    /** @test */
    public function toolCanBeAddedToDatabase()
    {
        $tool = new Tool();
        $tool->setName('Github');
        $tool->setImgSrc('https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg');

        $this->entityManager->persist($tool);
        $this->entityManager->flush();

        $toolRecord = $this->repository->find(1);


        self::assertEquals('Github', $toolRecord->getName());
        self::assertEquals('https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg', $toolRecord->getImgSrc());

    }

    /** @test */
    public function toolsAreOrderedByOrderValue()
    {
        $tool2 = new Tool();
        $tool2->setName('Github');
        $tool2->setImgSrc('https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg');
        $tool2->setOrderValue(2);

        $tool1 = new Tool();
        $tool1->setName('Symfony');
        $tool1->setImgSrc('https://cdn.jsdelivr.net/gh/devicons/devicon/icons/symfony/symfony-original.svg');
        $tool1->setOrderValue(1);

        $this->entityManager->persist($tool2);
        $this->entityManager->persist($tool1);
        $this->entityManager->flush();


        $tools = $this->repository->findAllOrderBy('orderValue');


        self::assertEquals('Symfony', $tools[0]->getName());
        self::assertEquals('Github', $tools[1]->getName());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Tool::class);
    }

}
