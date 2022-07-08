<?php

namespace App\Tests;

use App\Entity\Heading;
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


        $this->entityManager->persist($heading);
        $this->entityManager->flush();

        $headingRepository = $this->entityManager->getRepository(Heading::class);

        $headingRecord = $headingRepository->findOneBy(['textID'=> 'about-me']);
        $headingEnRecord = $headingRecord->translate('en');
        $headingPlRecord = $headingRecord->translate('pl');


        $this->assertEquals('Hi, I\'m Ahmed', $headingEnRecord->getName());
        $this->assertEquals('Cześć, jestem Ahmed', $headingPlRecord->getName());
    }

    /** @test */
    public function headingsAreUniqueByTextID()
    {
        $this->expectException(UniqueConstraintViolationException::class);
        $heading1 = new Heading();
        $heading1->setTextID('contact');

        $heading2 =new Heading();
        $heading2->setTextID('contact');

        $this->entityManager->persist($heading1);
        $this->entityManager->persist($heading2);
        $this->entityManager->flush();

    }

}