<?php

namespace App\Tests;

use App\Entity\Paragraph;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ParagraphTest extends DatabaseDependantWebTestCase
{
    /** @test */
    public function paragraphCanBeAddedInBothLanguages()
    {
        $paragraph = new Paragraph();
        $paragraph->setTextID('about-me');
        $paragraphEn = $paragraph->translate('en');

        $paragraphEn->setTitle('About me');
        $paragraphEn->setDescription(
            'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus animi ducimus eaque quis quod rerum ut. Consequatur debitis error, ipsam itaque laborum magni minima molestias non omnis quas reiciendis sed.'
        );

        $paragraphPl = $paragraph->translate('pl');
        $paragraphPl->setTitle('O mnie');
        $paragraphPl->setDescription(
            'Zażółć gęślą jaźń'
        );


        $this->entityManager->persist($paragraphPl);
        $this->entityManager->persist($paragraphEn);
        $this->entityManager->persist($paragraph);
        $this->entityManager->flush();

        $paragraphRepository = $this->entityManager->getRepository(Paragraph::class);
        $paragraphRecord = $paragraphRepository->findOneBy(['textID' => 'about-me']);
        $paragraphEnRecord = $paragraphRecord->translate('en');
        $paragraphPlRecord = $paragraphRecord->translate('pl');


        $this->assertEquals('About me', $paragraphEnRecord->getTitle());
        $this->assertEquals(
            'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus animi ducimus eaque quis quod rerum ut. Consequatur debitis error, ipsam itaque laborum magni minima molestias non omnis quas reiciendis sed.',
            $paragraphEnRecord->getDescription()
        );
        $this->assertEquals('O mnie', $paragraphPlRecord->getTitle());
        $this->assertEquals('Zażółć gęślą jaźń', $paragraphPlRecord->getDescription());
    }

    /** @test */
    public function paragraphsAreUniqueByTextID()
    {
        $this->expectException(UniqueConstraintViolationException::class);

        $paragraph1 = new Paragraph();
        $paragraph1->setTextID('about-me');

        $paragraph2 = new Paragraph();
        $paragraph2->setTextID('about-me');


        $this->entityManager->persist($paragraph1);
        $this->entityManager->persist($paragraph2);

        $this->entityManager->flush();
    }

    /** @test */
    public function paragraphTitleAndDescriptionAreNullByDefault()
    {
        $paragraph = new Paragraph();
        $paragraph->setTextID('contact');

        $this->entityManager->persist($paragraph);
        $this->entityManager->flush();

        $paragraphRepository = $this->entityManager->getRepository(Paragraph::class);
        $paragraphRecord = $paragraphRepository->findOneBy(['textID' => 'contact']);
        $paragraphEnRecord = $paragraphRecord->translate('en');
        $paragraphPlRecord = $paragraphRecord->translate('pl');

        $this->assertEquals(null, $paragraphEnRecord->getTitle());
        $this->assertEquals(null, $paragraphEnRecord->getDescription());
        $this->assertEquals(null, $paragraphPlRecord->getTitle());
        $this->assertEquals(null, $paragraphPlRecord->getDescription());
    }

}