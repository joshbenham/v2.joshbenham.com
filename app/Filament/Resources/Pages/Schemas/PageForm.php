<?php

declare(strict_types=1);

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Page Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, ?string $state, callable $set) => $operation === 'create' && $state !== null ? $set('slug', Str::slug($state)) : null),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->helperText('URL-friendly version of the title'),

                        RichEditor::make('content')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'h2',
                                'h3',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'codeBlock',
                                'undo',
                                'redo',
                            ]),
                    ]),

                Section::make('Publishing')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(false),
                    ]),

                Section::make('SEO & Meta Tags')
                    ->collapsed()
                    ->schema([
                        TextInput::make('seo.meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('Recommended: 50-60 characters. Leave empty to use page title.'),

                        Textarea::make('seo.meta_description')
                            ->label('Meta Description')
                            ->maxLength(160)
                            ->rows(3)
                            ->helperText('Recommended: 150-160 characters for optimal display in search results.'),

                        TextInput::make('seo.meta_keywords')
                            ->label('Meta Keywords')
                            ->helperText('Comma-separated keywords (optional, less important for modern SEO).'),

                        TextInput::make('seo.canonical')
                            ->label('Canonical URL')
                            ->url()
                            ->helperText('Leave empty to use the page URL automatically.'),

                        Select::make('seo.robots')
                            ->label('Robots')
                            ->options([
                                'index,follow' => 'Index & Follow (Default)',
                                'noindex,follow' => 'No Index, Follow',
                                'index,nofollow' => 'Index, No Follow',
                                'noindex,nofollow' => 'No Index, No Follow',
                            ])
                            ->default('index,follow'),
                    ]),

                Section::make('Open Graph (Facebook)')
                    ->collapsed()
                    ->schema([
                        TextInput::make('seo.og_title')
                            ->label('OG Title')
                            ->maxLength(60)
                            ->helperText('Leave empty to use Meta Title or Page Title.'),

                        Textarea::make('seo.og_description')
                            ->label('OG Description')
                            ->maxLength(200)
                            ->rows(3)
                            ->helperText('Leave empty to use Meta Description.'),

                        FileUpload::make('seo.og_image')
                            ->label('OG Image')
                            ->image()
                            ->directory('seo-images')
                            ->maxSize(2048)
                            ->helperText('Recommended: 1200x630px. Displays when shared on Facebook, LinkedIn.'),

                        Select::make('seo.og_type')
                            ->label('OG Type')
                            ->options([
                                'website' => 'Website',
                                'article' => 'Article',
                                'product' => 'Product',
                            ])
                            ->default('website'),
                    ]),

                Section::make('Twitter Card')
                    ->collapsed()
                    ->schema([
                        Select::make('seo.twitter_card')
                            ->label('Twitter Card Type')
                            ->options([
                                'summary' => 'Summary',
                                'summary_large_image' => 'Summary with Large Image',
                            ])
                            ->default('summary_large_image'),

                        TextInput::make('seo.twitter_title')
                            ->label('Twitter Title')
                            ->maxLength(70)
                            ->helperText('Leave empty to use OG Title or Page Title.'),

                        Textarea::make('seo.twitter_description')
                            ->label('Twitter Description')
                            ->maxLength(200)
                            ->rows(2)
                            ->helperText('Leave empty to use OG Description or Meta Description.'),

                        FileUpload::make('seo.twitter_image')
                            ->label('Twitter Image')
                            ->image()
                            ->directory('seo-images')
                            ->maxSize(2048)
                            ->helperText('Recommended: 1200x675px. Leave empty to use OG Image.'),
                    ]),

                Section::make('Structured Data (Schema.org)')
                    ->collapsed()
                    ->schema([
                        Select::make('seo.schema_type')
                            ->label('Schema Type')
                            ->options([
                                'WebPage' => 'Web Page',
                                'Article' => 'Article',
                                'BlogPosting' => 'Blog Post',
                                'Product' => 'Product',
                                'Event' => 'Event',
                                'Organization' => 'Organization',
                                'Person' => 'Person',
                                'LocalBusiness' => 'Local Business',
                                'FAQPage' => 'FAQ Page',
                            ])
                            ->live()
                            ->helperText('Select the most appropriate schema type for this page.'),

                        KeyValue::make('seo.schema_data')
                            ->label('Schema Properties')
                            ->keyLabel('Property')
                            ->valueLabel('Value')
                            ->addButtonLabel('Add Property')
                            ->visible(fn (callable $get): bool => $get('seo.schema_type') !== null)
                            ->helperText('Add custom schema.org properties. Common ones: headline, datePublished, author, image, description.'),
                    ]),
            ]);
    }
}
