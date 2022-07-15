<?php

namespace App\Tests\Service;

use App\Entity\Credential;
use App\Entity\Heading;
use App\Entity\Paragraph;
use App\Entity\TimelineCategory;
use App\Service\TranslatableContentException;
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

        $headings = $this->contentGenerator->generateTranslatableTextIDArrayContent(Heading::class, $locale);

        $this->assertEquals(1, $headings['homepage-1']['id']);
        $this->assertEquals(2, $headings['homepage-2']['id']);
        $this->assertEquals(3, $headings['homepage-3']['id']);
        $this->assertEquals('Cześć, jestem Ahmed', $headings['homepage-1']['name']);
        $this->assertEquals('Interesuję się programowaniem', $headings['homepage-2']['name']);
        $this->assertEquals('Dzięki tej stronie dowiesz się o mnie więcej', $headings['homepage-3']['name']);

    }

    /** @test
     * @throws TranslatableContentException
     */
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

        $headings = $this->contentGenerator->generateTranslatableTextIDArrayContent(Heading::class, $locale);

        $this->assertEquals(1, $headings['homepage-1']['id']);
        $this->assertEquals(2, $headings['homepage-2']['id']);
        $this->assertEquals(3, $headings['homepage-3']['id']);
        $this->assertEquals('Hi, I\'m Ahmed', $headings['homepage-1']['name']);
        $this->assertEquals('I\'m interested in programming', $headings['homepage-2']['name']);
        $this->assertEquals('You can learn about me on this website', $headings['homepage-3']['name']);

    }

    /** @test
     * @throws TranslatableContentException
     */
    public function paragraphsAreGeneratedInPl()
    {
        $locale = 'pl';

        $paragraph = new Paragraph();
        $paragraph->setTextID('about_me');
        $paragraphPl = $paragraph->translate('pl');
        $paragraphPl->setTitle('O mnie');
        $paragraphPl->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur, cumque.');

        $paragraph2 = new Paragraph();
        $paragraph2->setTextID('my_intentions');
        $paragraph2Pl = $paragraph2->translate('pl');
        $paragraph2Pl->setTitle('Co zamierzam');
        $paragraph2Pl->setDescription('Pracować, uczyć się itp.');

        $this->entityManager->persist($paragraph);
        $this->entityManager->persist($paragraph2);
        $this->entityManager->flush();

        $paragraphs = $this->contentGenerator->generateTranslatableTextIDArrayContent(Paragraph::class, $locale);

        self::assertSame(1, $paragraphs['about_me']['id']);
        self::assertSame(2, $paragraphs['my_intentions']['id']);
        self::assertEquals('O mnie', $paragraphs['about_me']['title']);
        self::assertEquals('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur, cumque.', $paragraphs['about_me']['description']);
        self::assertEquals('Co zamierzam', $paragraphs['my_intentions']['title']);
        self::assertEquals('Pracować, uczyć się itp.', $paragraphs['my_intentions']['description']);

    }

    /** @test */
    public function timelinesAreGeneratedByTimelineCategoriesInPl()
    {
        $locale = 'pl';

        $timelineCategory = new TimelineCategory();
        $timelineCategoryPl = $timelineCategory->translate($locale);
        $timelineCategoryPl->setName('Edukacja');

        $timelineCategory2 = new TimelineCategory();
        $timelineCategory2Pl = $timelineCategory2->translate($locale);
        $timelineCategory2Pl->setName('Doświadczenie');

        $this->entityManager->persist($timelineCategory);
        $this->entityManager->persist($timelineCategoryPl);
        $this->entityManager->persist($timelineCategory2);
        $this->entityManager->persist($timelineCategory2Pl);
        $this->entityManager->flush();

        $this->client->request('GET', $this->router->generate(
            'timeline_new', ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);
        $this->client->submitForm('btn-save', [
            'timeline[title]' => 'Tytuł',
            'timeline[subtitle]' => 'Podtytuł',
            'timeline[date][year]' => 2018,
            'timeline[date][month]' => 6,
            'timeline[date][day]' => 1,
            'timeline[dateRange]' => 'czerwiec 2018 - sierpień 2019',
            'timeline[link]' => 'test link',
            'timeline[timelineCategory]' => $timelineCategory
        ]);

        $this->client->request('GET', $this->router->generate(
            'timeline_new', ['_locale' => $locale]));

        $this->client->submitForm('btn-save', [
            'timeline[title]' => 'Tytuł2',
            'timeline[subtitle]' => 'Podtytuł2',
            'timeline[date][year]' => 2019,
            'timeline[date][month]' => 5,
            'timeline[date][day]' => 1,
            'timeline[dateRange]' => 'maj 2019 - czerwiec 2019',
            'timeline[link]' => 'test link2',
            'timeline[timelineCategory]' => $timelineCategory2
        ]);

        $this->client->request('GET', $this->router->generate(
            'timeline_new', ['_locale' => $locale]));

        $this->client->submitForm('btn-save', [
            'timeline[title]' => 'Tytuł3',
            'timeline[subtitle]' => 'Podtytuł3',
            'timeline[date][year]' => 2017,
            'timeline[date][month]' => 2,
            'timeline[date][day]' => 1,
            'timeline[dateRange]' => 'luty 2017 - czerwiec 2020',
            'timeline[link]' => 'test link3',
            'timeline[timelineCategory]' => $timelineCategory2
        ]);

        $timelineCategories = $this->contentGenerator->generateTranslatableCollectionContent(TimelineCategory::class, $locale);


        self::assertFalse(isset($timelineCategories[0]['timelines'][0]['categoryName']));

        self::assertEquals('Edukacja', $timelineCategories[0]['name']);
        self::assertEquals('Tytuł', $timelineCategories[0]['timelines'][0]['title']);
        self::assertEquals('Podtytuł', $timelineCategories[0]['timelines'][0]['subtitle']);
        self::assertEquals('test link', $timelineCategories[0]['timelines'][0]['link']);
        self::assertEquals('czerwiec 2018 - sierpień 2019', $timelineCategories[0]['timelines'][0]['dateRange']);
        self::assertEquals('2018-06-01', $timelineCategories[0]['timelines'][0]['date']->format('Y-m-d'));

        self::assertEquals('Doświadczenie', $timelineCategories[1]['name']);
        self::assertEquals('Tytuł2', $timelineCategories[1]['timelines'][0]['title']);
        self::assertEquals('Podtytuł2', $timelineCategories[1]['timelines'][0]['subtitle']);
        self::assertEquals('test link2', $timelineCategories[1]['timelines'][0]['link']);
        self::assertEquals('maj 2019 - czerwiec 2019', $timelineCategories[1]['timelines'][0]['dateRange']);
        self::assertEquals('2019-05-01', $timelineCategories[1]['timelines'][0]['date']->format('Y-m-d'));

        self::assertEquals('Tytuł3', $timelineCategories[1]['timelines'][1]['title']);
        self::assertEquals('Podtytuł3', $timelineCategories[1]['timelines'][1]['subtitle']);
        self::assertEquals('test link3', $timelineCategories[1]['timelines'][1]['link']);
        self::assertEquals('luty 2017 - czerwiec 2020', $timelineCategories[1]['timelines'][1]['dateRange']);
        self::assertEquals('2017-02-01', $timelineCategories[1]['timelines'][1]['date']->format('Y-m-d'));

    }

    /** @test
     * @throws TranslatableContentException
     */
    public function credentialTranslationArrayIsGeneratedInPl()
    {
        $locale = 'pl';
        $this->client->request('GET', $this->router->generate(
            'credential_new',
            ['_locale' => $locale]));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('btn-save', [
            'credential[description]' => 'Testowy opis referencji',
            'credential[author]' => 'Testowy autor'
        ]);

        $this->client->request('GET', $this->router->generate(
            'credential_new',
            ['_locale' => $locale]));

        $this->client->submitForm('btn-save', [
            'credential[description]' => 'Testowy opis referencji2',
            'credential[author]' => 'Testowy autor2'
        ]);

        $credentials = $this->contentGenerator->generateTranslatableContent(Credential::class, $locale);

        self::assertEquals('Testowy opis referencji', $credentials[0]['description']);
        self::assertEquals('Testowy autor', $credentials[0]['author']);
        self::assertEquals('Testowy opis referencji2', $credentials[1]['description']);
        self::assertEquals('Testowy autor2', $credentials[1]['author']);
    }

// TODO   /** @test */
    public function translatableGeneratorCannotGenerateForNonTranslatableEntity()
    {
        $this->expectException(TranslatableContentException::class);
    }

// TODO   /** @test */
    public function translatableGeneratorCannotGenerateTextIDArrayIfThereIsNoTextIDInEntity()
    {
        $this->expectException(TranslatableContentException::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->contentGenerator = new TranslatableContentGenerator($this->entityManager);
    }

}
