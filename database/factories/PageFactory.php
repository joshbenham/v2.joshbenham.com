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
        ];
    }

    /**
     * Indicate that the page should be published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => true,
        ]);
    }

    /**
     * Indicate that the page should be unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => false,
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

    /**
     * Indicate that the page should have SEO data.
     */
    public function withSeo(): static
    {
        return $this->state(fn (array $attributes): array => [
            'seo' => [
                'meta_title' => fake()->sentence(6),
                'meta_description' => fake()->sentence(20),
                'og_title' => fake()->sentence(6),
                'og_description' => fake()->sentence(15),
                'schema_type' => 'WebPage',
            ],
        ]);
    }
}
