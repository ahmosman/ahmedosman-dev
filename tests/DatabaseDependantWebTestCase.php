<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

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

        $this->client->loginUser($this->getAdminTestUser('admin'));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    protected function getAdminTestUser(string $username)
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $passwordHasherFactory = new PasswordHasherFactory([
            PasswordAuthenticatedUserInterface::class => ['algorithm' => 'auto']
        ]);
        $passwordHasher = new UserPasswordHasher($passwordHasherFactory);

        $user = new User();
        $user->setUsername($username);
        $user->setRoles(["ROLE_ADMIN"]);
        $hashedPassword = $passwordHasher->hashPassword($user, 'qwerty');
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $userRepository->find(1);
    }
}