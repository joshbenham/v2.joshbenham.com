<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
final class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(3, true),
            'order' => fake()->numberBetween(0, 100),
            'is_published' => fake()->boolean(70),
            'is_homepage' => false,
            'published_at' => fake()->boolean(70) ? now() : null,
        ];
    }

    /**
     * Indicate that the page should be published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the page should be unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the page should be the homepage.
     */
    public function homepage(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_homepage' => true,
        ]);
    }
}
