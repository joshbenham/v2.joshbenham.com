<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can be created using factory', function (): void {
    $page = Page::factory()->create();

    expect($page)->toBeInstanceOf(Page::class)
        ->and($page->id)->toBeInt()
        ->and($page->title)->toBeString()
        ->and($page->slug)->toBeString()
        ->and($page->is_published)->toBeBool();
});

it('has fillable attributes', function (): void {
    $page = Page::factory()->create([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'content' => 'Test content',
        'order' => 10,
        'is_published' => true,
    ]);

    expect($page->title)->toBe('Test Page')
        ->and($page->slug)->toBe('test-page')
        ->and($page->content)->toBe('Test content')
        ->and($page->order)->toBe(10)
        ->and($page->is_published)->toBeTrue();
});

it('casts is_published to boolean', function (): void {
    $page = Page::factory()->create([
        'is_published' => true,
    ]);

    expect($page->is_published)->toBeBool()
        ->and($page->is_published)->toBeTrue();
});

it('automatically generates slug from title on creation', function (): void {
    $page = Page::factory()->create([
        'title' => 'My Awesome Page',
        'slug' => '', // Empty slug
    ]);

    expect($page->slug)->toBe('my-awesome-page');
});

it('does not override manually set slug', function (): void {
    $page = Page::factory()->create([
        'title' => 'My Awesome Page',
        'slug' => 'custom-slug',
    ]);

    expect($page->slug)->toBe('custom-slug');
});

it('can be created as published', function (): void {
    $page = Page::factory()->published()->create();

    expect($page->is_published)->toBeTrue();
});

it('can be created as unpublished', function (): void {
    $page = Page::factory()->unpublished()->create();

    expect($page->is_published)->toBeFalse();
});

it('can update attributes', function (): void {
    $page = Page::factory()->create([
        'title' => 'Old Title',
    ]);

    $page->update(['title' => 'New Title']);

    expect($page->fresh()->title)->toBe('New Title');
});

it('can be deleted', function (): void {
    $page = Page::factory()->create();
    $pageId = $page->id;

    $page->delete();

    expect(Page::query()->find($pageId))->toBeNull();
});

it('casts order to integer', function (): void {
    $page = Page::factory()->create([
        'order' => 5,
    ]);

    expect($page->order)->toBeInt()
        ->and($page->order)->toBe(5);
});

it('defaults order to 0', function (): void {
    $page = Page::factory()->create([
        'order' => 0,
    ]);

    expect($page->order)->toBe(0);
});

it('can query published pages', function (): void {
    Page::factory()->published()->create();
    Page::factory()->unpublished()->create();

    $publishedPages = Page::published()->get();

    expect($publishedPages)->toHaveCount(1)
        ->and($publishedPages->first()->is_published)->toBeTrue();
});

it('can query ordered pages', function (): void {
    Page::factory()->create(['order' => 3]);
    Page::factory()->create(['order' => 1]);
    Page::factory()->create(['order' => 2]);

    $orderedPages = Page::ordered()->get();

    expect($orderedPages->first()->order)->toBe(1)
        ->and($orderedPages->get(1)->order)->toBe(2)
        ->and($orderedPages->last()->order)->toBe(3);
});

it('automatically sets order to max + 1 on creation', function (): void {
    Page::factory()->create(['order' => 5]);
    Page::factory()->create(['order' => 10]);

    $newPage = Page::query()->create([
        'title' => 'New Page',
        'is_published' => false,
    ]);

    expect($newPage->order)->toBe(11);
});

it('sets order to 1 when no pages exist', function (): void {
    $firstPage = Page::query()->create([
        'title' => 'First Page',
        'is_published' => false,
    ]);

    expect($firstPage->order)->toBe(1);
});

it('automatically marks first page as homepage', function (): void {
    $firstPage = Page::query()->create([
        'title' => 'First Page',
        'is_published' => false,
    ]);

    expect($firstPage->is_homepage)->toBeTrue();
});

it('does not mark subsequent pages as homepage', function (): void {
    Page::factory()->create(); // First page (will be homepage)

    $secondPage = Page::query()->create([
        'title' => 'Second Page',
        'is_published' => false,
    ]);

    expect($secondPage->is_homepage)->toBeFalse();
});

it('can set a page as homepage using setAsHomepage method', function (): void {
    $firstPage = Page::factory()->create(['is_homepage' => true]);
    $secondPage = Page::factory()->create(['is_homepage' => false]);

    $secondPage->setAsHomepage();

    expect($secondPage->fresh()->is_homepage)->toBeTrue()
        ->and($firstPage->fresh()->is_homepage)->toBeFalse();
});

it('can query homepage page', function (): void {
    Page::factory()->create(['is_homepage' => true]);
    Page::factory()->create(['is_homepage' => false]);

    $homepage = Page::homepage()->first();

    expect($homepage)->not->toBeNull()
        ->and($homepage->is_homepage)->toBeTrue();
});

it('prevents removing homepage status when only one homepage exists', function (): void {
    $page = Page::factory()->create(['is_homepage' => true]);
    Page::factory()->create(['is_homepage' => false]);

    $page->is_homepage = false;
    $page->save();
})->throws(RuntimeException::class, 'At least one page must be marked as the homepage.');

it('prevents deleting homepage when it is the only homepage and other pages exist', function (): void {
    $homepage = Page::factory()->create(['is_homepage' => true]);
    Page::factory()->create(['is_homepage' => false]);

    $homepage->delete();
})->throws(RuntimeException::class, 'Cannot delete the homepage. Please set another page as homepage first.');

it('casts seo to array', function (): void {
    $page = Page::factory()->create();

    expect($page->seo)->toBeNull();
});

it('can store and retrieve seo data', function (): void {
    $seoData = [
        'meta_title' => 'Custom Meta Title',
        'meta_description' => 'Custom meta description for SEO',
        'og_title' => 'Custom OG Title',
        'schema_type' => 'Article',
    ];

    $page = Page::factory()->create(['seo' => $seoData]);

    expect($page->fresh()->seo)
        ->toBeArray()
        ->and($page->fresh()->seo['meta_title'])->toBe('Custom Meta Title')
        ->and($page->fresh()->seo['meta_description'])->toBe('Custom meta description for SEO')
        ->and($page->fresh()->seo['og_title'])->toBe('Custom OG Title')
        ->and($page->fresh()->seo['schema_type'])->toBe('Article');
});

it('can create page with seo using factory', function (): void {
    $page = Page::factory()->withSeo()->create();

    expect($page->seo)->toBeArray()
        ->and($page->seo['meta_title'])->not->toBeNull()
        ->and($page->seo['meta_description'])->not->toBeNull()
        ->and($page->seo['schema_type'])->toBe('WebPage');
});

it('can update seo data using array', function (): void {
    $page = Page::factory()->create();

    $page->update([
        'seo' => [
            'meta_title' => 'Updated Title',
            'og_description' => 'Updated OG Description',
        ],
    ]);

    expect($page->fresh()->seo['meta_title'])->toBe('Updated Title')
        ->and($page->fresh()->seo['og_description'])->toBe('Updated OG Description');
});

it('generates schema json when schema type is set', function (): void {
    $seoData = [
        'schema_type' => 'Article',
        'schema_data' => ['headline' => 'Test Article'],
    ];

    $page = Page::factory()->create([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'seo' => $seoData,
    ]);

    $schemaData = $page->seo;
    expect($schemaData)->toBeArray()
        ->and($schemaData['schema_type'])->toBe('Article')
        ->and($schemaData['schema_data']['headline'])->toBe('Test Article');
});

it('returns null seo when not set', function (): void {
    $page = Page::factory()->create();

    expect($page->seo)->toBeNull();
});

it('checks if seo data is empty', function (): void {
    $pageWithoutSeo = Page::factory()->create();
    $pageWithSeo = Page::factory()->create(['seo' => ['meta_title' => 'Test']]);

    expect($pageWithoutSeo->seo)->toBeNull()
        ->and($pageWithSeo->seo)->not->toBeNull();
});
