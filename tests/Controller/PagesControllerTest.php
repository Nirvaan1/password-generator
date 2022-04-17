<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PagesControllerTest extends WebTestCase
{
    /** @test */
    public function homapage_is_displayed_successfuly(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Password Generator');
        $this->assertPageTitleSame('Password Generator');
    }

    /** @test */
    public function generated_password_page_is_displayed_successfully(): void
    {
        // "client" that is acting as the browser
        $client = static::createClient();
        $crawler = $client->request('GET', '/generate-password');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Password generated !');
        $this->assertPageTitleSame('Password generated');
    }

    /** @test */
    public function cookies_are_not_present_when_visiting_homepage_for_the_first_time(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertBrowserNotHasCookie('app_length');
        $this->assertBrowserNotHasCookie('app_uppercase_letters');
        $this->assertBrowserNotHasCookie('app_digits');
        $this->assertBrowserNotHasCookie('app_special_characters');

    }

    /** @test */
    public function cookies_are_present_when_visiting_for_the_first_time_generated_password(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/generate-password');

        $this->assertBrowserHasCookie('app_length');
        $this->assertBrowserHasCookie('app_uppercase_letters');
        $this->assertBrowserHasCookie('app_digits');
        $this->assertBrowserHasCookie('app_special_characters');
    }

    /** @test  */
    public function password_generator_should_work_when_submit_form(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $crawler = $client->submitForm('Generate Password', [], 'GET');

        $this->assertRouteSame('app_generate_password');
        $this->assertSame(12,mb_strlen( $crawler->filter('.alert.alert-success > strong')->text()));

        $client->clickLink('Go back to the homepage');
        $this->assertRouteSame('app_home');
    }

    /** @test  */
    public function password_generator_workflow_from_submit_form_to_generated_password_page_and_go_back_to_the_homepage(): void
    {
        $client = static::createClient();

        $client->request('GET','/');

        $crawler = $client->submitForm(
            'Generate Password',
            [
                'length' => 12,
                'uppercase_letters' => true,
                'special_characters' => true,
                'digits' => true
            ],
            'GET'
        );

        $this->assertRouteSame('app_generate_password', );
    }
}
