<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class PageController extends Controller
{
    /**
     * Display the homepage.
     */
    public function home(): Factory|View
    {
        $page = Page::query()
            ->homepage()
            ->published()
            ->firstOrFail();

        // Check for custom view for homepage
        $view = $this->getViewForPage($page);

        return view($view, [
            'page' => $page,
            'isHomepage' => true,
        ]);
    }

    /**
     * Display the specified page.
     */
    public function show(string $slug): Factory|View|RedirectResponse
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Redirect if trying to access homepage by slug
        if ($page->is_homepage) {
            return redirect()->route('home');
        }

        // Check for custom view for this page
        $view = $this->getViewForPage($page);

        return view($view, [
            'page' => $page,
            'isHomepage' => false,
        ]);
    }

    /**
     * Get the view name for a page, checking for custom views first.
     */
    private function getViewForPage(Page $page): string
    {
        $customView = "pages.{$page->slug}";

        if (view()->exists($customView)) {
            return $customView;
        }

        return 'pages.show';
    }
}
