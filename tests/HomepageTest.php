<?php

namespace App\Tests;

use App\Entity\Homepage;

class HomepageTest extends DatabaseDependantTestCase
{

    /** @test */
    public function homepageRecordCanBeCreatedInDatabaseInEnglish()
    {
        $homepage = new Homepage();
        $homepage->setHeading('Hello on my site');
        $homepage->setSubheading('I\'m interested in programming');

        $this->entityManager->persist($homepage);
        $this->entityManager->flush();

        $homepageRepository = $this->entityManager->getRepository(
            Homepage::class
        );
        $homepageRecord = $homepageRepository->findOneBy(
            ['heading' => 'Hello on my site']
        );

        $this->assertEquals('Hello on my site', $homepageRecord->getHeading());
        $this->assertEquals(
            'I\'m interested in programming',
            $homepageRecord->getSubheading()
        );
    }

    /** @test */
    public function homepageRecordCanBeCreatedInDatabaseInPolish()
    {
        $homepage = new Homepage();
        $homepage->setHeading('Cześć na mojej stronie!');
        $homepage->setSubheading('Zażółć gęślą jaźń');

        $this->entityManager->persist($homepage);
        $this->entityManager->flush();

        $homepageRepository = $this->entityManager->getRepository(
            Homepage::class
        );
        $homepageRecord = $homepageRepository->findOneBy(
            ['heading' => 'Cześć na mojej stronie!']
        );

        $this->assertEquals(
            'Cześć na mojej stronie!',
            $homepageRecord->getHeading()
        );
        $this->assertEquals(
            'Zażółć gęślą jaźń',
            $homepageRecord->getSubheading()
        );
    }

    /** @test */
    public function homepageTranslationRecordCanBeCreatedInDatabaseInEnglish()
    {
        $homepage = new Homepage();
        $homepageTranslateEnglish = $homepage->translate('en');
        $homepageTranslateEnglish->setHeading('Hello on my site');
        $homepageTranslateEnglish->setSubheading(
            'I\'m interested in programming'
        );

        $this->entityManager->persist($homepage);
        $this->entityManager->flush();

        $homepageRepository = $this->entityManager->getRepository(
            Homepage::class
        );
        $homepageRecord = $homepageRepository->findOneBy(
            ['heading' => 'Hello on my site']
        );

        $homepageTranslateEnglishRecord = $homepageRecord->translate('en');

        $this->assertEquals(
            'Hello on my site',
            $homepageTranslateEnglishRecord->getHeading()
        );
        $this->assertEquals(
            'I\'m interested in programming',
            $homepageTranslateEnglishRecord->getSubheading()
        );
    }

//    /** @test
//     */
//    public function homepageTranslationRecordCanBeCreatedInDatabaseInPolish()
//    {
//
//    }
}