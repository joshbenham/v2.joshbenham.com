<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\PageObserver;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $content
 * @property int $order
 * @property bool $is_published
 * @property bool $is_homepage
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
#[ObservedBy(PageObserver::class)]
final class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'order',
        'is_published',
        'is_homepage',
        'published_at',
    ];

    /**
     * Scope a query to only include published pages.
     *
     * @param  Builder<Page>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * Scope a query to only include pages ordered by the order field.
     *
     * @param  Builder<Page>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('order');
    }

    /**
     * Scope a query to only include the homepage.
     *
     * @param  Builder<Page>  $query
     */
    public function scopeHomepage(Builder $query): void
    {
        $query->where('is_homepage', true);
    }

    /**
     * Set this page as the homepage.
     */
    public function setAsHomepage(): void
    {
        self::query()->where('is_homepage', true)->update(['is_homepage' => false]);
        $this->update(['is_homepage' => true]);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_published' => 'boolean',
            'is_homepage' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
}
