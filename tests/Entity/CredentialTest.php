<?php

namespace App\Tests\Entity;

use App\Entity\Credential;
use App\Repository\CredentialRepository;
use App\Tests\DatabaseDependantWebTestCase;

class CredentialTest extends DatabaseDependantWebTestCase
{
    private CredentialRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Credential::class);
    }

    /** @test */
    public function credentialCanBeAddedInBothLanguages()
    {
        $credential = new Credential();
        $credentialEn = $credential->translate('en');
        $credentialEn->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eveniet, iste?');
        $credentialEn->setAuthor('Test author');

        $credentialPl = $credential->translate('pl');
        $credentialPl->setDescription('Testowy opis');
        $credentialPl->setAuthor('Testowy autor');

        $this->entityManager->persist($credential);
        $this->entityManager->flush();

        $credentialRecord = $this->repository->find(1);
        $credentialEnRecord = $credentialRecord->translate('en');
        $credentialPlRecord = $credentialRecord->translate('pl');


        self::assertEquals('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eveniet, iste?', $credentialEnRecord->getDescription());
        self::assertEquals('Test author', $credentialEnRecord->getAuthor());
        self::assertEquals('Testowy opis', $credentialPlRecord->getDescription());
        self::assertEquals('Testowy autor', $credentialPlRecord->getAuthor());
    }
}
