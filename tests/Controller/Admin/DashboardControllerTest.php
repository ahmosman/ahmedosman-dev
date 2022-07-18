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
        $this->assertEquals($this->router->generate('heading_new'), $this->removeWhiteCharacters($crawler->filter('.new_link')->attr('href')));

    }

    /** @test */
    public function dashboardParagraphRequestIsPassingAndDisplayingParagraphs()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_paragraph', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);

        $this->assertEquals('<th>TextID</th><th>Title</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));
        $this->assertEquals($this->router->generate('paragraph_new'), $this->removeWhiteCharacters($crawler->filter('.new_link')->attr('href')));
    }

    /** @test */
    public function dashboardToolRequestIsPassingAndDisplayingTools()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_tool', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);

        $this->assertEquals('<th>Name</th><th>OrderValue</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));
        $this->assertEquals($this->router->generate('tool_new'), $this->removeWhiteCharacters($crawler->filter('.new_link')->attr('href')));
    }

    /** @test */
    public function dashboardTimelineCategoryRequestIsPassingAndDisplayingTimelineCategories()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_timeline-category', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);

        $this->assertEquals('<th>ID</th><th>Name</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));
        $this->assertEquals($this->router->generate('timeline-category_new'), $this->removeWhiteCharacters($crawler->filter('.new_link')->attr('href')));
    }

    /** @test */
    public function dashboardTimelineRequestIsPassingAndDisplayingTimelineCategories()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_timeline', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);

        $this->assertEquals('<th>ID</th><th>Title</th><th>Timelinecategory</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));
        $this->assertEquals($this->router->generate('timeline_new'), $this->removeWhiteCharacters($crawler->filter('.new_link')->attr('href')));
    }

    /** @test */
    public function dashboardCredentialRequestIsPassingAndDisplayingCredentials()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_credential', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);

        $this->assertEquals('<th>ID</th><th>Author</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));
        $this->assertEquals($this->router->generate('credential_new'), $this->removeWhiteCharacters($crawler->filter('.new_link')->attr('href')));
    }

    /** @test */
    public function dashboardProjectSlideRequestIsPassingAndDisplayingProjectSlides()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_project-slide', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);

        $this->assertEquals('<th>ID</th><th>Description</th><th>Ordervalue</th><th>Imagefilename</th><th>Project</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));
        $this->assertEquals($this->router->generate('project-slide_new'), $this->removeWhiteCharacters($crawler->filter('.new_link')->attr('href')));
    }


    /** @test */
    public function dashboardProjectRequestIsPassingAndDisplayingProjects()
    {
        $crawler = $this->client->request('GET', $this->router->generate('dashboard_project', ['_locale' => $this->locale]));
        self::assertResponseStatusCodeSame(200);

        $this->assertEquals('<th>ID</th><th>Title</th><th>Ordervalue</th><th>Imagefilename</th><th>Actions</th>', $this->removeWhiteCharacters($crawler->filter('.dashboard__list tr')->first()->html()));
        $this->assertEquals($this->router->generate('project_new'), $this->removeWhiteCharacters($crawler->filter('.new_link')->attr('href')));
    }

}
