<?php

namespace App\Tests\Service;

use App\Entity\Heading;
use App\Service\NonExistingTextIDException;
use App\Service\TranslatableContentGenerator;
use App\Tests\DatabaseDependantWebTestCase;

class TranslatableContentGeneratorTest extends DatabaseDependantWebTestCase
{
    private TranslatableContentGenerator $contentGenerator;

    /** @test */
    public function headingsAreGeneratedInPl()
    {
        $locale = 'pl';

        $heading = new Heading();
        $heading->setTextID('homepage-1');

        $headingPl = $heading->translate($locale);
        $headingPl->setName('Cześć, jestem Ahmed');

        $heading2 = new Heading();
        $heading2->setTextID('homepage-2');

        $heading2Pl = $heading2->translate($locale);
        $heading2Pl->setName('Interesuję się programowaniem');

        $heading3 = new Heading();
        $heading3->setTextID('homepage-3');

        $heading3Pl = $heading3->translate($locale);
        $heading3Pl->setName('Dzięki tej stronie dowiesz się o mnie więcej');


        $this->entityManager->persist($heading);
        $this->entityManager->persist($heading2);
        $this->entityManager->persist($heading3);
        $this->entityManager->flush();

        $headings = $this->contentGenerator->generateContentArrayForTextID(Heading::class, ['homepage-1', 'homepage-2', 'homepage-3'], $locale);

        $this->assertEquals(1,$headings['homepage-1']['id']);
        $this->assertEquals(2,$headings['homepage-2']['id']);
        $this->assertEquals(3,$headings['homepage-3']['id']);
        $this->assertEquals('Cześć, jestem Ahmed', $headings['homepage-1']['name']);
        $this->assertEquals('Interesuję się programowaniem', $headings['homepage-2']['name']);
        $this->assertEquals('Dzięki tej stronie dowiesz się o mnie więcej', $headings['homepage-3']['name']);

    }

    /** @test */
    public function headingsAreGeneratedInEn()
    {
        $locale = 'en';

        $heading = new Heading();
        $heading->setTextID('homepage-1');

        $headingEn = $heading->translate($locale);
        $headingEn->setName('Hi, I\'m Ahmed');

        $heading2 = new Heading();
        $heading2->setTextID('homepage-2');

        $heading2En = $heading2->translate($locale);
        $heading2En->setName('I\'m interested in programming');

        $heading3 = new Heading();
        $heading3->setTextID('homepage-3');

        $heading3En = $heading3->translate($locale);
        $heading3En->setName('You can learn about me on this website');

        $this->entityManager->persist($heading);
        $this->entityManager->persist($heading2);
        $this->entityManager->persist($heading3);
        $this->entityManager->flush();

        $headings = $this->contentGenerator->generateContentArrayForTextID(Heading::class, ['homepage-1', 'homepage-2', 'homepage-3'], $locale);

        $this->assertEquals(1,$headings['homepage-1']['id']);
        $this->assertEquals(2,$headings['homepage-2']['id']);
        $this->assertEquals(3,$headings['homepage-3']['id']);
        $this->assertEquals('Hi, I\'m Ahmed', $headings['homepage-1']['name']);
        $this->assertEquals('I\'m interested in programming', $headings['homepage-2']['name']);
        $this->assertEquals('You can learn about me on this website', $headings['homepage-3']['name']);

    }

    /** @test */
    public function generatorCannotGenerateNonExistingTextID()
    {
        $this->expectException(NonExistingTextIDException::class);

        $locale = 'pl';
        $heading = new Heading();
        $heading->setTextID('homepage-1');

        $this->entityManager->persist($heading);
        $this->entityManager->flush();

        $this->contentGenerator->generateContentArrayForTextID(Heading::class, ['homepage-1', 'homepage-2'], $locale);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->contentGenerator = new TranslatableContentGenerator($this->entityManager);
    }

}
