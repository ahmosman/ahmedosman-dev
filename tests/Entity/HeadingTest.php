<?php

namespace App\Tests\Entity;

use App\Entity\Heading;
use App\Tests\DatabaseDependantWebTestCase;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class HeadingTest extends DatabaseDependantWebTestCase
{
    /** @test */
    public function HeadingsCanBeAddedInBothLanguages()
    {
        $heading = new Heading();
        $heading->setTextID('about-me');

        $headingEn = $heading->translate('en');
        $headingEn->setName('Hi, I\'m Ahmed');

        $headingPl = $heading->translate('pl');
        $headingPl->setName('Cześć, jestem Ahmed');

        $heading2 = new Heading();
        $heading2->setTextID('homepage-2');

        $heading2Pl = $heading2->translate('pl');
        $heading2Pl->setName('Interesuję się programowaniem');

        $heading2En = $heading2->translate('en');
        $heading2En->setName('I\'m interested in programming');


        $this->entityManager->persist($heading);
        $this->entityManager->persist($heading2);
        $this->entityManager->flush();

        $headingRepository = $this->entityManager->getRepository(Heading::class);

        $headingRecord = $headingRepository->findOneBy(['textID' => 'about-me']);
        $headingEnRecord = $headingRecord->translate('en');
        $headingPlRecord = $headingRecord->translate('pl');
        $heading2Record = $headingRepository->findOneBy(['textID' => 'homepage-2']);
        $heading2EnRecord = $heading2Record->translate('en');
        $heading2PlRecord = $heading2Record->translate('pl');


        $this->assertEquals('Hi, I\'m Ahmed', $headingEnRecord->getName());
        $this->assertEquals('Cześć, jestem Ahmed', $headingPlRecord->getName());
        $this->assertEquals('I\'m interested in programming', $heading2EnRecord->getName());
        $this->assertEquals('Interesuję się programowaniem', $heading2PlRecord->getName());
    }

    /** @test */
    public function headingsAreUniqueByTextID()
    {
        $this->expectException(UniqueConstraintViolationException::class);
        $heading1 = new Heading();
        $heading1->setTextID('contact');

        $heading2 = new Heading();
        $heading2->setTextID('contact');

        $this->entityManager->persist($heading1);
        $this->entityManager->persist($heading2);
        $this->entityManager->flush();

    }

}