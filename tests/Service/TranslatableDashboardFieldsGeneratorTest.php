<?php

namespace App\Tests\Service;

use App\Entity\Paragraph;
use App\Service\TranslatableDashboardFieldsGenerator;
use App\Tests\DatabaseDependantWebTestCase;


class TranslatableDashboardFieldsGeneratorTest extends DatabaseDependantWebTestCase
{
    /** @test */
    public function tableDashboardCanBeGeneratedForParagraphEntityInPl()
    {
        $locale = 'pl';

        $paragraph1 = new Paragraph();
        $paragraph1->setTextID('about-me');

        $paragraph1Pl = $paragraph1->translate($locale);
        $paragraph1Pl->setTitle('O mnie');
        $paragraph1Pl->setDescription(
            'Zażółć gęślą jaźń'
        );

        $paragraph2 = new Paragraph();
        $paragraph2->setTextID('my-intentions');

        $paragraph2Pl = $paragraph2->translate($locale);
        $paragraph2Pl->setTitle('Co zamierzam');
        $paragraph2Pl->setDescription(
            'Accusamus animi ducimus eaque quis quod rerum ut.'
        );


        $this->entityManager->persist($paragraph1Pl);
        $this->entityManager->persist($paragraph1);

        $this->entityManager->persist($paragraph2Pl);
        $this->entityManager->persist($paragraph2);

        $this->entityManager->flush();

        $paragraphRepository = $this->entityManager->getRepository(Paragraph::class);
        $paragraphRecords = $paragraphRepository->findAll();

        $generatedArray = (new TranslatableDashboardFieldsGenerator($paragraphRecords, ['id', 'textID'], ['title', 'description'], $locale))->generate();
        self::assertEquals(1, $generatedArray[0]['id']);
        self::assertEquals('about-me', $generatedArray[0]['textID']);
        self::assertEquals('O mnie', $generatedArray[0]['title']);
        self::assertEquals('Zażółć gęślą jaźń', $generatedArray[0]['description']);

        self::assertEquals(2, $generatedArray[1]['id']);
        self::assertEquals('my-intentions', $generatedArray[1]['textID']);
        self::assertEquals('Co zamierzam', $generatedArray[1]['title']);
        self::assertEquals('Accusamus animi ducimus eaque quis quod rerum ut.', $generatedArray[1]['description']);
    }

    /** @test */
    public function tableDashboardCanBeGeneratedForParagraphEntityInEn()
    {
        $locale = 'en';
        $paragraph1 = new Paragraph();
        $paragraph1->setTextID('about-me');
        $paragraph1En = $paragraph1->translate($locale);

        $paragraph1En->setTitle('About me');
        $paragraph1En->setDescription(
            'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus animi ducimus eaque quis quod rerum ut. Consequatur debitis error, ipsam itaque laborum magni minima molestias non omnis quas reiciendis sed.'
        );

        $paragraph2 = new Paragraph();
        $paragraph2->setTextID('my-intentions');
        $paragraph2En = $paragraph2->translate($locale);
        $paragraph2En->setTitle('My intentions');
        $paragraph2En->setDescription(
            'Lorem ipsum dolor sit amet, consectetur adipisicing elit.'
        );


        $this->entityManager->persist($paragraph1En);
        $this->entityManager->persist($paragraph1);

        $this->entityManager->persist($paragraph2En);
        $this->entityManager->persist($paragraph2);

        $this->entityManager->flush();

        $paragraphRepository = $this->entityManager->getRepository(Paragraph::class);
        $paragraphRecords = $paragraphRepository->findAll();

        $generatedArray = (new TranslatableDashboardFieldsGenerator($paragraphRecords, ['id', 'textID'], ['title', 'description'], $locale))->generate();
        self::assertEquals(1, $generatedArray[0]['id']);
        self::assertEquals('about-me', $generatedArray[0]['textID']);
        self::assertEquals('About me', $generatedArray[0]['title']);
        self::assertEquals('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus animi ducimus eaque quis quod rerum ut. Consequatur debitis error, ipsam itaque laborum magni minima molestias non omnis quas reiciendis sed.', $generatedArray[0]['description']);

        self::assertEquals(2, $generatedArray[1]['id']);
        self::assertEquals('my-intentions', $generatedArray[1]['textID']);
        self::assertEquals('My intentions', $generatedArray[1]['title']);
        self::assertEquals('Lorem ipsum dolor sit amet, consectetur adipisicing elit.', $generatedArray[1]['description']);
    }
}
