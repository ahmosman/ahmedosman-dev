<?php

namespace App\Tests;

use App\Entity\Homepage;
use App\Entity\HomepageTranslation;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;

class HomepageTest extends DatabaseDependantTestCase
{

    /** @test */
    public function homepageRecordCannotBeCreatedInDatabaseWithoutUsingTranslation()
    {
        $this->expectException(NotNullConstraintViolationException::class);

        $homepageTranslation = new HomepageTranslation();
        $homepageTranslation->setHeading('Hello on my site');
        $homepageTranslation->setSubheading('I\'m interested in programming');

        $this->entityManager->persist($homepageTranslation);
        $this->entityManager->flush();
    }

    /** @test */
    public function homepageTranslationRecordCanBeCreatedInDatabaseInEnglish()
    {
        $LOCALE = 'en';
        $homepage = new Homepage();
        $homepageTranslate = $homepage->translate($LOCALE);
        $homepageTranslate->setHeading('Hello on my site');
        $homepageTranslate->setSubheading(
            'I\'m interested in programming'
        );


        $this->entityManager->persist($homepage);

        $homepage->mergeNewTranslations();

        $this->entityManager->flush();

        $homepageRepository = $this->entityManager->getRepository(
            Homepage::class
        );

        $homepageRecordTranslate = $homepageRepository->find(1)->translate($LOCALE);


        $this->assertEquals(
            'Hello on my site',
            $homepageRecordTranslate->getHeading()
        );
        $this->assertEquals(
            'I\'m interested in programming',
            $homepageRecordTranslate->getSubheading()
        );
    }

    /** @test
     */
    public function homepageTranslationRecordCanBeCreatedInDatabaseInPolish()
    {
        $LOCALE = 'pl';
        $homepage = new Homepage();
        $homepageTranslate = $homepage->translate($LOCALE);
        $homepageTranslate->setHeading('Cześć na mojej stronie!');
        $homepageTranslate->setSubheading(
            'Zażółć gęślą jaźń'
        );


        $this->entityManager->persist($homepage);

        $homepage->mergeNewTranslations();

        $this->entityManager->flush();

        $homepageRepository = $this->entityManager->getRepository(
            Homepage::class
        );

        $homepageRecordTranslate = $homepageRepository->find(1)->translate($LOCALE);


        $this->assertEquals(
            'Cześć na mojej stronie!',
            $homepageRecordTranslate->getHeading()
        );
        $this->assertEquals(
            'Zażółć gęślą jaźń',
            $homepageRecordTranslate->getSubheading()
        );
    }
}