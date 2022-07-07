<?php

namespace App\Tests\TestCases;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DatabaseDependantWebTestCase extends WebTestCase
{
    protected EntityManagerInterface|null $entityManager;
    protected KernelBrowser $client;
    protected Router $router;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $kernel = $this->client->getKernel();

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')
            ->getManager();

        $this->router = $this->client->getContainer()->get('router');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}