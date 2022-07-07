<?php

namespace App\Tests;

use App\Entity\Paragraph;
use App\Tests\TestCases\LocaleAndDatabaseDependantWebTestCase;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Gedmo\Translatable\Entity\Translation;

class ParagraphTest extends LocaleAndDatabaseDependantWebTestCase
{
    /** @test */
    public function paragraphCanBeAddedWithPlAndEnLocale()
    {
        $this->requestToMainWithLocale('pl');

        $translationRepository = $this->entityManager->getRepository(Translation::class);

        $paragraph = new Paragraph();
        $paragraph->setTextID('about-me');
        $paragraph->setTitle('O mnie');
        $paragraph->setContent(
            'Zażółć gęślą jaźń'
        );

        $translationRepository->translate($paragraph, 'title','en','About me')
            ->translate($paragraph,'content','en', 'my english paragraph content');
        $this->entityManager->persist($paragraph);
        $this->entityManager->flush();
        $translation = $translationRepository->findTranslations($paragraph);

        $paragraphRepository = $this->entityManager->getRepository(Paragraph::class);
        $paragraphRecord = $paragraphRepository->findOneBy(['textID' => 'about-me']);
        $paragraphRecord->setTranslatableLocale('en');
        dump($paragraphRecord->getTitle());
        $paragraphRecord->setTranslatableLocale('pl');
        dump($paragraphRecord->getTitle());


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
    public function paragraphTitleAndcontentAreNullByDefault()
    {
        $paragraph = new Paragraph();
        $paragraph->setTextID('contact');

        $this->entityManager->persist($paragraph);
        $this->entityManager->flush();

        $paragraphRepository = $this->entityManager->getRepository(Paragraph::class);
        $paragraphRecord = $paragraphRepository->findOneBy(['textID'=> 'contact']);
        $paragraphEnRecord = $paragraphRecord->translate('en');
        $paragraphPlRecord = $paragraphRecord->translate('pl');

        $this->assertEquals(null, $paragraphEnRecord->getTitle());
        $this->assertEquals(null, $paragraphEnRecord->getContent());
        $this->assertEquals(null, $paragraphPlRecord->getTitle());
        $this->assertEquals(null, $paragraphPlRecord->getContent());
    }

}