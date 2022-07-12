<?php

namespace App\Tests\Controller\Admin;

use App\Service\StringFormatTrait;
use App\Tests\DatabaseDependantWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DashboardControllerTest extends DatabaseDependantWebTestCase
{
    use StringFormatTrait;

    private string $locale = 'pl';

    /** @test */
    public function dashboardRequestIsRedirectingToHeading()
    {
        $this->client->request('GET', $this->router->generate('dashboard', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
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
