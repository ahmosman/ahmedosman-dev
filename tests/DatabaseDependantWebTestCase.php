<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DatabaseDependantWebTestCase extends WebTestCase
{
    protected EntityManagerInterface|null $entityManager;
    protected KernelBrowser $client;
    protected Router|null $router;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $kernel = $this->client->getKernel();

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')
            ->getManager();

        $this->router = $kernel->getContainer()->get('router');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}