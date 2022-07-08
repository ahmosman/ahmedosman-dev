<?php

namespace App\Tests\Controller\Admin;

use App\Service\StringFormatTrait;
use App\Tests\DatabaseDependantWebTestCase;
use DOMElement;

class DashboardControllerWebTest extends DatabaseDependantWebTestCase
{
    use StringFormatTrait;

    /** @test */
    public function dashboardRequestIsPassingAndDisplayingMenu()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString($this->removeWhiteCharacters('Heading Paragraph'),$this->removeWhiteCharacters($crawler->filter('.dashboard__menu')->text()));
    }

    /** @test */
    public function dashboardHeadingRequestIsPassingAndDisplayingHeadings()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_heading'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('<th>TextID</th><th>Name</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));

    }

    /** @test */
    public function dashboardParagraphRequestIsPassingAndDisplayingParagraphs()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_paragraph'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('<th>TextID</th><th>Title</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));
    }

}
