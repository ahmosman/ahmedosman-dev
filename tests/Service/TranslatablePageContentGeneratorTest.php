<?php

namespace App\Tests\Service;

use App\Entity\Heading;
use App\Service\TranslatablePageContentGenerator;
use App\Tests\DatabaseDependantWebTestCase;

class TranslatablePageContentGeneratorTest extends DatabaseDependantWebTestCase
{
    private TranslatablePageContentGenerator $contentGenerator;

    /** @test */
    public function headingsAreGeneratedInPl()
    {
        $locale = 'pl';

        $heading = new Heading();
        $heading->setTextID('homepage-1');

        $headingPl = $heading->translate('pl');
        $headingPl->setName('Cześć, jestem Ahmed');

        $heading2 = new Heading();
        $heading2->setTextID('homepage-2');

        $heading2Pl = $heading2->translate('pl');
        $heading2Pl->setName('Interesuję się programowaniem');

        $heading3 = new Heading();
        $heading3->setTextID('homepage-3');

        $heading3Pl = $heading3->translate('pl');
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->contentGenerator = new TranslatablePageContentGenerator($this->entityManager);
    }

}
