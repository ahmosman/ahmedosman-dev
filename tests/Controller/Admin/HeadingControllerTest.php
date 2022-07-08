<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Heading;
use App\Repository\HeadingRepository;
use App\Tests\DatabaseDependantWebTestCase;

class HeadingControllerTest extends DatabaseDependantWebTestCase
{
    private HeadingRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Heading::class);
    }

    /** @test */
    public function newHeadingCanBeCreatedInPl()
    {
        $locale = 'pl';
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $this->client->request('GET', $this->router->generate('heading_new',['_locale' => $locale]));

        self::assertResponseStatusCodeSame(200);
        $this->client->submitForm('btn-save',[
           'heading[textID]' => 'about-me',
           'heading[name]' => 'Kim jestem, dokąd zmierzam'
        ]);
        $headingRecord = $this->repository->findOneBy(['textID' => 'about-me']);
        $headingTranslation = $headingRecord->translate($locale);


        self::assertEquals('about-me',$headingRecord->getTextID());
        self::assertEquals('Kim jestem, dokąd zmierzam', $headingTranslation->getName());
        self::assertResponseRedirects($this->router->generate('dashboard_paragraph'));
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

    }


}
