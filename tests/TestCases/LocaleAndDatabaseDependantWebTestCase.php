<?php

namespace App\Tests\TestCases;

class LocaleAndDatabaseDependantWebTestCase extends DatabaseDependantWebTestCase
{
    public function requestToMainWithLocale(string $locale): void
    {
        $this->client->request('GET', $this->router->generate('main',['_locale' => $locale]));
    }
}