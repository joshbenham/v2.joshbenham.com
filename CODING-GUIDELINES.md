=== foundation rules ===

# VS Code Coding Guidelines

The coding guidelines are specifically curated for this application. These guidelines should be followed closely to enhance satisfaction building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystem packages & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.3.28
- filament/filament (FILAMENT) - v4.2.3
- laravel/framework (LARAVEL) - v12.40.2
- laravel/prompts (PROMPTS) - v0.3.8
- livewire/livewire (LIVEWIRE) - v3.7.0
- larastan/larastan (LARASTAN) - v3.8.0
- laravel/mcp (MCP) - v0.3.4
- laravel/pint (PINT) - v1.26.0
- laravel/sail (SAIL) - v1.48.1
- pestphp/pest (PEST) - v4.1.5
- phpunit/phpunit (PHPUNIT) - v12.4.4
- rector/rector (RECTOR) - v2.2.8
- tailwindcss (TAILWINDCSS) - v4.1.17

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, \`isRegisteredForDiscounts\`, not \`discount()\`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run \`npm run build\`, \`npm run dev\`, or \`composer run dev\`. Ask them.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors

- Use PHP 8 constructor property promotion in \`__construct()\`.
- Do not allow empty \`__construct()\` methods with zero parameters.

### Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

## Comments

- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something very complex going on.

## PHPDoc Blocks

- Add useful array shape type definitions for arrays when appropriate.

## Enums

- Typically, keys in an Enum should be TitleCase. For example: \`FavoritePerson\`, \`BestLake\`, \`Monthly\`.

=== laravel/core rules ===

## Do Things the Laravel Way

- Use \`php artisan make:\` commands to create new files (i.e. migrations, controllers, models, etc.).
- If you're creating a generic PHP class, use \`php artisan make:class\`.
- Pass \`--no-interaction\` to all Artisan commands to ensure they work without user input.

### Database

- Always use proper Eloquent relationship methods with return type hints.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid \`DB::\`; prefer \`Model::query()\`.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not.

### Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers.
- Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues

- Use queued jobs for time-consuming operations with the \`ShouldQueue\` interface.

### Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation

- When generating links to other pages, prefer named routes and the \`route()\` function.

### Configuration

- Use environment variables only in configuration files.
- Never use the \`env()\` function directly outside of config files.
- Always use \`config('app.name')\`, not \`env('APP_NAME')\`.

### Testing

- When creating models for tests, use the factories for the models.
- Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as \`\$this->faker->word()\` or \`fake()->randomDigit()\`.
- When creating tests, use \`php artisan make:test --pest <name>\` to create a feature test, and pass \`--unit\` to create a unit test.
- Most tests should be feature tests.

### Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, run \`npm run build\` or ask the user to run \`npm run dev\` or \`composer run dev\`.

=== laravel/v12 rules ===

## Laravel 12

### Laravel 12 Structure

- No middleware files in \`app/Http/Middleware/\`.
- \`bootstrap/app.php\` is the file to register middleware, exceptions, and routing files.
- \`bootstrap/providers.php\` contains application specific service providers.
- No \`app/Console/Kernel.php\` - use \`bootstrap/app.php\` or \`routes/console.php\` for console configuration.
- Commands auto-register - files in \`app/Console/Commands/\` are automatically available.

### Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column.
- Laravel 12 allows limiting eagerly loaded records natively: \`\$query->latest()->limit(10);\`.

### Models

- Casts can and likely should be set in a \`casts()\` method on a model rather than the \`\$casts\` property.

=== livewire/core rules ===

## Livewire Core

- Use \`php artisan make:livewire\` to create new components.
- State should live on the server, with the UI reflecting it.
- All Livewire requests hit the Laravel backend - always validate form data and run authorization checks.

## Livewire Best Practices

- Livewire components require a single root element.
- Use \`wire:loading\` and \`wire:dirty\` for delightful loading states.
- Add \`wire:key\` in loops for proper tracking.
- Prefer lifecycle hooks like \`mount()\`, \`updatedFoo()\` for initialization and reactive side effects.

=== livewire/v3 rules ===

## Livewire 3

### Key Changes

- Use \`wire:model.live\` for real-time updates, \`wire:model\` is now deferred by default.
- Components now use the \`App\Livewire\` namespace (not \`App\Http\Livewire\`).
- Use \`\$this->dispatch()\` to dispatch events (not \`emit\` or \`dispatchBrowserEvent\`).

### Alpine

- Alpine is now included with Livewire, don't manually include Alpine.js.
- Plugins included: persist, intersect, collapse, and focus.

=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run \`vendor/bin/pint --dirty\` before finalizing changes.
- Do not run \`vendor/bin/pint --test\`, simply run \`vendor/bin/pint\` to fix formatting issues.

=== pest/core rules ===

## Pest

### Testing

- If you need to verify a feature is working, write or update a Unit / Feature test.

### Pest Tests

- All tests must be written using Pest. Use \`php artisan make:test --pest <name>\`.
- You must not remove any tests or test files from the tests directory without approval.
- Tests should test all of the happy paths, failure paths, and weird paths.
- Tests live in the \`tests/Feature\` and \`tests/Unit\` directories.

### Running Tests

- Run the minimal number of tests using an appropriate filter before finalizing code edits.
- To run all tests: \`php artisan test\`.
- To run all tests in a file: \`php artisan test tests/Feature/ExampleTest.php\`.
- To filter on a particular test name: \`php artisan test --filter=testName\`.
- When the tests relating to your changes are passing, ask the user if they would like to run the entire test suite.

### Pest Assertions

- When asserting status codes on a response, use the specific method like \`assertForbidden\` and \`assertNotFound\` instead of using \`assertStatus(403)\`.

### Mocking

- Mocking can be very helpful when appropriate.
- When mocking, you can use the \`Pest\Laravel\mock\` function, but always import it via \`use function Pest\Laravel\mock;\` before using it.
- Alternatively, you can use \`\$this->mock()\` if existing tests do.

### Datasets

- Use datasets in Pest to simplify tests which have a lot of duplicated data.
- This is often the case when testing validation rules.

=== pest/v4 rules ===

## Pest 4

- Pest v4 offers: browser testing, smoke testing, visual regression testing, test sharding, and faster type coverage.
- Browser testing is incredibly powerful and useful for this project.
- Browser tests should live in \`tests/Browser/\`.

### Browser Testing

- You can use Laravel features like \`Event::fake()\`, \`assertAuthenticated()\`, and model factories within Pest v4 browser tests.
- Use \`RefreshDatabase\` when needed to ensure a clean state for each test.
- Interact with the page (click, type, scroll, select, submit, drag-and-drop, etc.) when appropriate.
- If requested, test on multiple browsers (Chrome, Firefox, Safari).
- If requested, test on different devices and viewports.
- Switch color schemes (light/dark mode) when appropriate.

=== tailwindcss/core rules ===

## Tailwind Core

- Use Tailwind CSS classes to style HTML.
- Check and use existing tailwind conventions within the project before writing your own.
- Offer to extract repeated patterns into components that match the project's conventions.
- Think through class placement, order, priority, and defaults.
- Remove redundant classes, add classes to parent or child carefully to limit repetition.

### Spacing

- When listing items, use gap utilities for spacing, don't use margins.

### Dark Mode

- If existing pages and components support dark mode, new pages and components must support dark mode in a similar way, typically using \`dark:\`.

=== tailwindcss/v4 rules ===

## Tailwind 4

- Always use Tailwind CSS v4 - do not use the deprecated utilities.
- \`corePlugins\` is not supported in Tailwind v4.
- In Tailwind v4, configuration is CSS-first using the \`@theme\` directive.
- In Tailwind v4, you import Tailwind using a regular CSS \`@import\` statement, not using the \`@tailwind\` directives.

### Replaced Utilities

- Tailwind v4 removed deprecated utilities. Do not use the deprecated option - use the replacement.

| Deprecated | Replacement |
|------------|-------------|
| bg-opacity-* | bg-black/* |
| text-opacity-* | text-black/* |
| border-opacity-* | border-black/* |
| divide-opacity-* | divide-black/* |
| ring-opacity-* | ring-black/* |
| placeholder-opacity-* | placeholder-black/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |

=== filament/core rules ===

## Filament

- Filament is a Server-Driven UI (SDUI) framework for Laravel built on top of Livewire, Alpine.js, and Tailwind CSS.
- Utilize static \`make()\` methods for consistent component initialization.

### Artisan

- You must use the Filament specific Artisan commands to create new files or components for Filament.
- Inspect the required options, always pass \`--no-interaction\`, and valid arguments for other options when applicable.

### Filament's Core Features

- **Actions**: Handle doing something within the application, often with a button or link. They encapsulate the UI, interactive modal, and logic.
- **Forms**: Dynamic forms rendered within other features.
- **Infolists**: Read-only lists of data.
- **Notifications**: Flash notifications displayed to users.
- **Panels**: Top-level container that can include all other features.
- **Resources**: Static classes used to build CRUD interfaces for Eloquent models. Live in \`app/Filament/Resources\`.
- **Schemas**: Components that define the structure and behavior of the UI.
- **Tables**: Interactive tables with filtering, sorting, pagination, and more.
- **Widgets**: Small components included within dashboards.

### Relationships

- Determine if you can use the \`relationship()\` method on form components when you need \`options\` for a select, checkbox, repeater, or when building a \`Fieldset\`.

### Testing

- It's important to test Filament functionality.
- Ensure that you are authenticated to access the application within the test.
- Filament uses Livewire, so start assertions with \`livewire()\` or \`Livewire::test()\`.

### Important Version 4 Changes

- File visibility is now \`private\` by default.
- The \`deferFilters\` method is now the default behavior, so users must click a button before filters are applied. Use \`deferFilters(false)\` to disable.
- The \`Grid\`, \`Section\`, and \`Fieldset\` layout components no longer span all columns by default.
- The \`all\` pagination page method is not available for tables by default.
- All action classes extend \`Filament\Actions\Action\`.
- Form & Infolist layout components have been moved to \`Filament\Schemas\Components\`.
- A new \`Repeater\` component for Forms has been added.
- Icons now use the \`Filament\Support\Icons\Heroicon\` Enum by default.

### Organize Component Classes Structure

- Schema components: \`Schemas/Components/\`
- Table columns: \`Tables/Columns/\`
- Table filters: \`Tables/Filters/\`
- Actions: \`Actions/\`

=== tests rules ===

## Test Enforcement

- Every change must be programmatically tested.
- Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed.
- Use \`php artisan test\` with a specific filename or filter.
