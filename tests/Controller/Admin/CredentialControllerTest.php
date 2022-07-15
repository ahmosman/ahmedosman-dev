<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Credential;
use App\Repository\CredentialRepository;
use App\Tests\DatabaseDependantWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CredentialControllerTest extends DatabaseDependantWebTestCase
{
    private CredentialRepository $repository;

    /** @test */
    public function newCredentialCanBeCreatedInPl(): void
    {
        $locale = 'pl';
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $this->client->request('GET', $this->router->generate(
            'credential_new',
            ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'credential[description]' => 'Testowy opis referencji',
            'credential[author]' => 'Testowy autor'
        ]);

        $credentialRecord = $this->repository->find(1);
        $credentialTranslation = $credentialRecord->translate($locale);

        self::assertEquals('Testowy opis referencji', $credentialTranslation->getDescription());
        self::assertEquals('Testowy autor', $credentialTranslation->getAuthor());
        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_credential'));

    }

    /** @test */
    public function credentialCanBeEditedDependingOnLocale(): void
    {
        $locale = 'pl';
        $this->client->request('GET', $this->router->generate('credential_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'credential[description]' => 'Testowy edit opis referencji',
            'credential[author]' => 'Edit Testowy autor'
        ]);

        $this->client->request('GET', $this->router->generate('credential_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-update', [
            'credential[description]' => 'Testowy opis referencji',
            'credential[author]' => 'Testowy autor'
        ]);

        $locale = 'en';
        $this->client->request('GET', $this->router->generate('credential_edit', [
            '_locale' => $locale,
            'id' => 1
        ]));
        self::assertResponseStatusCodeSame(200);


        $this->client->submitForm('btn-update', [
            'credential[description]' => 'Test credential description',
            'credential[author]' => 'Test author'
        ]);

        $credentialRecord = $this->repository->find(1);
        $credentialPl = $credentialRecord->translate('pl');
        $credentialEn = $credentialRecord->translate('en');


        self::assertEquals('Testowy opis referencji', $credentialPl->getDescription());
        self::assertEquals('Testowy autor', $credentialPl->getAuthor());
        self::assertEquals('Test credential description', $credentialEn->getDescription());
        self::assertEquals('Test author', $credentialEn->getAuthor());
        self::assertResponseRedirects($this->router->generate('dashboard_credential', ['_locale' => 'en']));
    }

    /** @test */
    public function credentialCanBeRemoved(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $locale = 'pl';
        $this->client->request('GET', $this->router->generate(
            'credential_new',
            ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'credential[description]' => 'Testowy opis referencji',
            'credential[author]' => 'Testowy autor'
        ]);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        $this->client->request('GET', $this->router->generate('credential_delete', ['id' => 1]));
        self::assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);
        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects($this->router->generate('dashboard_credential'));

    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(Credential::class);
    }

}