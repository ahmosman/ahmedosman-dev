<?php

namespace App\Tests\Controller\Admin;

use App\Service\StringFormatTrait;
use App\Tests\DatabaseDependantWebTestCase;
use DOMElement;

class DashboardControllerTest extends DatabaseDependantWebTestCase
{
    use StringFormatTrait;
    private string $locale = 'pl';

    /** @test */
    public function dashboardRequestIsPassingAndDisplayingMenu()
    {

        $crawler = $this->client->request('GET', $this->router->generate('dashboard', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);
        $this->assertStringContainsString($this->removeWhiteCharacters('Heading Paragraph'),$this->removeWhiteCharacters($crawler->filter('.dashboard__menu')->text()));
    }

    /** @test */
    public function dashboardHeadingRequestIsPassingAndDisplayingHeadings()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_heading', ['_locale' => $this->locale]));

        self::assertResponseStatusCodeSame(200);
        $this->assertEquals('<th>TextID</th><th>Name</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));

    }

    /** @test */
    public function dashboardParagraphRequestIsPassingAndDisplayingParagraphs()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_paragraph', ['_locale' => $this->locale]));

        self::assertResponseStatusCodeSame(200);
        $this->assertEquals('<th>TextID</th><th>Title</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));
    }

}
