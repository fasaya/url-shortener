<?php

namespace Fasaya\UrlShortener\Tests\Unit;

use Illuminate\Support\Str;
use Fasaya\UrlShortener\Model\Link;
use Fasaya\UrlShortener\UrlShortener;
use Fasaya\UrlShortener\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Fasaya\UrlShortener\Exceptions\ValidationException;

class AdminControllerTest extends TestCase
{

    public $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = config('url-shortener.admin-route.as');
    }

    public function test_can_view_links_index_page()
    {
        $response = $this->get(route($this->route . 'index'));

        $response->assertStatus(200);
    }

    public function test_can_view_create_link_page()
    {
        $response = $this->get(route($this->route . 'create'));

        $response->assertStatus(200);
    }

    public function test_can_create_link()
    {
        $linkData = [
            'redirect_to' => 'https://example.com',
            'is_custom_checkbox' => 'on',
            'custom' => 'custom-slug',
            'have_expiration_date_checkbox' => 'on',
            'expiration_date' => '2023-09-25 13:16:25',
            'is_disabled' => 0
        ];

        $response = $this->post(route($this->route . 'store'), $linkData);

        $response->assertStatus(302); // Redirect after successful creation
        $this->assertDatabaseHas('short_links', [
            'slug' => $linkData['custom'],
            'short_url' => config('app.url') . config('url-shortener.uri', '/l') . '/' . $linkData['custom'],
            'long_url' => $linkData['redirect_to'],
            'expired_at' => $linkData['expiration_date'],
            'is_disabled' => $linkData['is_disabled']
        ]); // Verify the link was created in the database
    }

    public function test_can_view_edit_link_page()
    {
        $link = Link::factory()->create();

        $response = $this->get(route($this->route . 'edit', $link));

        $response->assertStatus(200);
    }

    public function test_can_update_link()
    {
        $link = Link::factory()->create();

        $updatedLinkData = [
            'have_expiration_date_checkbox' => 'on',
            'expiration_date' => '2023-09-25 13:16:25',
            'is_disabled' => 0
        ];

        $response = $this->put(route($this->route . 'update', $link), $updatedLinkData);

        $response->assertStatus(302); // Redirect after successful update
        $this->assertDatabaseHas('short_links', [
            'slug' => $link->slug,
            'short_url' => $link->short_url,
            'long_url' => $link->long_url,
            'expired_at' => $updatedLinkData['expiration_date'],
            'is_disabled' => $updatedLinkData['is_disabled']
        ]); // Verify the link was updated in the database
    }

    public function test_can_delete_link()
    {
        $link = Link::factory()->create();

        $response = $this->delete(route($this->route . 'destroy', $link->id));

        $response->assertStatus(302); // Redirect after successful deletion
        $this->assertSoftDeleted('short_links', ['id' => $link->id]); // Verify the link was deleted from the database
    }

    // Clean up (optional)
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
