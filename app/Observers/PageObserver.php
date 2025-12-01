<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

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
            $maxOrder = Page::query()->max('order');
            $page->order = ($maxOrder ?? 0) + 1;
        }

        // If this is the first page, make it the homepage
        if (! isset($page->is_homepage)) {
            $page->is_homepage = Page::query()->count() === 0;
        }
    }

    /**
     * Handle the Page "updating" event.
     */
    public function updating(Page $page): bool
    {
        // Prevent removing homepage status if it's the only page
        if ($page->isDirty('is_homepage') && $page->is_homepage === false && Page::query()->where('is_homepage', true)->count() === 1) {
            Notification::make()
                ->danger()
                ->title('Cannot remove homepage status')
                ->body('At least one page must be marked as the homepage.')
                ->send();

            return false;
        }

        return true;
    }

    /**
     * Handle the Page "deleting" event.
     */
    public function deleting(Page $page): bool
    {
        // Prevent deleting the homepage if it's the only page
        if ($page->is_homepage && Page::query()->where('is_homepage', true)->count() === 1 && Page::query()->count() > 1) {
            Notification::make()
                ->danger()
                ->title('Cannot delete homepage')
                ->body('Cannot delete the homepage. Please set another page as homepage first.')
                ->send();

            return false;
        }

        return true;
    }
}
