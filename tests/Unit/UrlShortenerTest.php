<?php

namespace Fasaya\UrlShortener\Tests\Unit;

use Fasaya\UrlShortener\Tests\TestCase;
use Fasaya\UrlShortener\UrlShortener;
use Fasaya\UrlShortener\Model\Link;
use Fasaya\UrlShortener\Exceptions\ValidationException;
use Illuminate\Support\Str;

class UrlShortenerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testMakeMethodCreatesLink()
    {
        // Arrange
        $longUrl = 'https://example.com';

        // Act
        $link = UrlShortener::make($longUrl);

        // Assert
        $this->assertInstanceOf(Link::class, $link);
        $this->assertEquals($longUrl, $link->long_url);
        $link->delete();
    }

    public function testMakeCustomMethodCreatesLinkWithCustomSlug()
    {
        // Arrange
        $longUrl = 'https://example.com';
        $customSlug = 'custom-slug';

        // Act
        $link = UrlShortener::makeCustom($longUrl, $customSlug);

        // Assert
        $this->assertInstanceOf(Link::class, $link);
        $this->assertEquals($longUrl, $link->long_url);
        $this->assertEquals($customSlug, $link->slug);
        $link->delete();
    }

    public function testExistsMethodReturnsTrueForExistingLink()
    {
        // Arrange
        $longUrl = 'https://example.com';
        $link = UrlShortener::make($longUrl);

        // Act
        $exists = UrlShortener::exists($link->short_url);

        // Assert
        $this->assertTrue($exists);
        $link->delete();
    }

    public function testExistsMethodReturnsFalseForNonExistingLink()
    {
        // Arrange
        $nonExistingShortUrl = 'https://non-existing-url.com';

        // Act
        $exists = UrlShortener::exists($nonExistingShortUrl);

        // Assert
        $this->assertFalse($exists);
    }

    public function testGenerateShortUrlGeneratesShortUrl()
    {
        // Arrange
        $longUrl = 'https://example.com';

        // Act
        $result = UrlShortener::generateShortUrl($longUrl);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('slug', $result);
        $this->assertArrayHasKey('url', $result);
    }

    public function testGenerateCustomUrlGeneratesCustomShortUrl()
    {
        // Arrange
        $customSlug = 'custom-slug';

        // Act
        $shortUrl = UrlShortener::generateCustomUrl($customSlug);

        // Assert
        $this->assertEquals(config('app.url') . config('url-shortener.uri', '/l') . '/' . $customSlug, $shortUrl);
    }

    public function testRedirectMethodRedirectsToLongUrl()
    {
        // Arrange
        $longUrl = 'https://example.com';
        $link = UrlShortener::make($longUrl);
        $request = $this->app['request']->create(config('url-shortener.uri', '/l'), 'GET', ['slug' => $link->slug]);

        // Act
        $response = UrlShortener::redirect($request, $link->short_url);

        // Assert
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertEquals($longUrl, $response->getTargetUrl());
        $this->assertEquals(301, $response->status());
        // $link->delete();
    }

    // Clean up (optional)
    protected function tearDown(): void
    {
        // Additional cleanup specific to your package, if needed
        parent::tearDown();
    }
}
