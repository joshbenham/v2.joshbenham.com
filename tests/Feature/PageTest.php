<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays the homepage', function (): void {
    $homepage = Page::factory()->create([
        'title' => 'Welcome Home',
        'content' => '<p>This is the homepage content.</p>',
        'is_homepage' => true,
        'is_published' => true,
    ]);

    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('Welcome Home')
        ->assertSee('This is the homepage content.');
});

it('displays a page by slug', function (): void {
    Page::factory()->create(['is_homepage' => true, 'is_published' => true]);

    $page = Page::factory()->create([
        'title' => 'About Us',
        'slug' => 'about-us',
        'content' => '<p>Learn more about our company.</p>',
        'is_published' => true,
    ]);

    $response = $this->get('/about-us');

    $response->assertOk()
        ->assertSee('About Us')
        ->assertSee('Learn more about our company.');
});

it('returns 404 for unpublished pages', function (): void {
    Page::factory()->create(['is_homepage' => true, 'is_published' => true]);

    Page::factory()->create([
        'slug' => 'draft-page',
        'is_published' => false,
    ]);

    $response = $this->get('/draft-page');

    $response->assertNotFound();
});

it('redirects homepage slug to root', function (): void {
    $homepage = Page::factory()->create([
        'slug' => 'home',
        'is_homepage' => true,
        'is_published' => true,
    ]);

    $response = $this->get('/home');

    $response->assertRedirect('/');
});

it('displays SEO meta tags', function (): void {
    $homepage = Page::factory()->withSeo()->create([
        'title' => 'Test Page',
        'is_homepage' => true,
        'is_published' => true,
    ]);

    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('name="description"', false)
        ->assertSee('property="og:title"', false)
        ->assertSee('property="twitter:card"', false);
});

it('displays schema.org JSON-LD when configured', function (): void {
    $page = Page::factory()->create([
        'title' => 'Article Page',
        'slug' => 'article',
        'is_published' => true,
        'seo' => [
            'schema_type' => 'Article',
            'schema_data' => [
                'headline' => 'Amazing Article',
                'author' => 'John Doe',
            ],
        ],
    ]);

    Page::factory()->create(['is_homepage' => true, 'is_published' => true]);

    $response = $this->get('/article');

    $response->assertOk()
        ->assertSee('application/ld+json', false)
        ->assertSee('"@type": "Article"', false)
        ->assertSee('Amazing Article', false);
});

it('shows navigation with ordered pages', function (): void {
    Page::factory()->create([
        'title' => 'Home',
        'is_homepage' => true,
        'is_published' => true,
        'order' => 1,
    ]);

    Page::factory()->create([
        'title' => 'About',
        'slug' => 'about',
        'is_published' => true,
        'order' => 2,
    ]);

    Page::factory()->create([
        'title' => 'Contact',
        'slug' => 'contact',
        'is_published' => true,
        'order' => 3,
    ]);

    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('About')
        ->assertSee('Contact')
        ->assertSeeInOrder(['About', 'Contact']); // Ordered by order field
});

it('uses custom view when it exists for a page', function (): void {
    Page::factory()->create(['is_homepage' => true, 'is_published' => true]);

    $page = Page::factory()->create([
        'title' => 'Custom Page',
        'slug' => 'custom',
        'content' => '<p>This should use a custom view.</p>',
        'is_published' => true,
    ]);

    // Create a custom view for this page
    $customViewPath = resource_path('views/pages/custom.blade.php');
    $customViewContent = <<<'BLADE'
<!DOCTYPE html>
<html>
<head>
    <title>Custom View for {{ $page->title }}</title>
</head>
<body>
    <h1>This is a custom view!</h1>
    <p>Page: {{ $page->title }}</p>
</body>
</html>
BLADE;

    file_put_contents($customViewPath, $customViewContent);

    $response = $this->get('/custom');

    $response->assertOk()
        ->assertSee('This is a custom view!')
        ->assertSee('Page: Custom Page');

    // Clean up
    unlink($customViewPath);
});

it('falls back to default view when custom view does not exist', function (): void {
    Page::factory()->create(['is_homepage' => true, 'is_published' => true]);

    $page = Page::factory()->create([
        'title' => 'Regular Page',
        'slug' => 'regular',
        'content' => '<p>This uses the default view.</p>',
        'is_published' => true,
    ]);

    $response = $this->get('/regular');

    $response->assertOk()
        ->assertSee('Regular Page')
        ->assertSee('This uses the default view.');
});

it('uses custom home view when homepage has slug home', function (): void {
    $homepage = Page::factory()->create([
        'title' => 'Welcome Home',
        'slug' => 'home',
        'content' => '<p>Homepage content here.</p>',
        'is_homepage' => true,
        'is_published' => true,
    ]);

    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('Custom Homepage View') // From home.blade.php
        ->assertSee('Welcome Home')
        ->assertSee('Homepage content here.');
});

it('displays custom 404 page for non-existent routes', function (): void {
    Page::factory()->create(['is_homepage' => true, 'is_published' => true]);

    $response = $this->get('/this-page-does-not-exist');

    $response->assertNotFound()
        ->assertSee('404')
        ->assertSee('Page Not Found')
        ->assertSee('Go Home');
});

it('displays custom 404 page with navigation to available pages', function (): void {
    Page::factory()->create(['is_homepage' => true, 'is_published' => true]);

    Page::factory()->create([
        'title' => 'About Us',
        'slug' => 'about',
        'is_published' => true,
    ]);

    Page::factory()->create([
        'title' => 'Contact',
        'slug' => 'contact',
        'is_published' => true,
    ]);

    $response = $this->get('/non-existent-page');

    $response->assertNotFound()
        ->assertSee('About Us')
        ->assertSee('Contact')
        ->assertSee('Or explore these pages');
});
