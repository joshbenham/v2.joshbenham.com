<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Str;
use RuntimeException;

final class PageObserver
{
    /**
     * Handle the Page "creating" event.
     */
    public function creating(Page $page): void
    {
        if (empty($page->slug)) {
            $page->slug = Str::slug($page->title);
        }

        if (! isset($page->order)) {
            /** @var int|null $maxOrder */
            $maxOrder = Page::max('order');
            $page->order = ($maxOrder ?? 0) + 1;
        }

        // If this is the first page, make it the homepage
        if (! isset($page->is_homepage)) {
            $page->is_homepage = Page::count() === 0;
        }

        // Set published_at when page is published but date not set
        if ($page->is_published && $page->published_at === null) {
            $page->published_at = now();
        }
    }

    /**
     * Handle the Page "updating" event.
     */
    public function updating(Page $page): void
    {
        // Prevent removing homepage status if it's the only page
        if ($page->isDirty('is_homepage') && $page->is_homepage === false) {
            if (Page::where('is_homepage', true)->count() === 1) {
                throw new RuntimeException('At least one page must be marked as the homepage.');
            }
        }

        // Set published_at when page becomes published but date not set
        if ($page->isDirty('is_published') && $page->is_published && $page->published_at === null) {
            $page->published_at = now();
        }

        // Clear published_at when page becomes unpublished
        if ($page->isDirty('is_published') && ! $page->is_published) {
            $page->published_at = null;
        }
    }

    /**
     * Handle the Page "deleting" event.
     */
    public function deleting(Page $page): void
    {
        // Prevent deleting the homepage if it's the only page
        if ($page->is_homepage && Page::where('is_homepage', true)->count() === 1 && Page::count() > 1) {
            throw new RuntimeException('Cannot delete the homepage. Please set another page as homepage first.');
        }
    }
}
