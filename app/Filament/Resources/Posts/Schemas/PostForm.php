<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;

// datum/tijd veld
use Filament\Forms\Components\FileUpload;

// bestand uploaden
use Filament\Forms\Components\Hidden;

// verborgen vaste waarden meesturen
use Filament\Forms\Components\Select;

// dropdowns en relaties
use Filament\Forms\Components\TextInput;

// tekstinput
use Filament\Forms\Components\Textarea;

// groter tekstveld
use Filament\Forms\Components\Toggle;

// boolean veld
use Filament\Schemas\Components\Section;

// formuliersecties
use Filament\Schemas\Components\Utilities\Get;

// huidige state uitlezen
use Filament\Schemas\Components\Utilities\Set;

// state aanpassen
use Filament\Schemas\Schema;

// schema-object
use Illuminate\Support\Str;

// slug genereren
class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([Section::make('Postgegevens') // hoofdsectie voor inhoud van de post
        ->schema([Select::make('user_id')->label('Auteur') // label in het formulier
        ->relationship('user', 'name') // toon user naam uit relatie
        ->required() // auteur is verplicht
        ->searchable() // dropdown doorzoekbaar maken
        ->preload(), // opties vooraf laden
            TextInput::make('title')->label('Titel') // label van titelveld
            ->required() // verplicht veld
            ->minLength(3) // minimum aantal karakters
            ->maxLength(255) // maximum aantal karakters
            ->live(onBlur: true) // pas reageren na verlaten van het veld
            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                if (($get('slug') ?? '') !== Str::slug((string)$old)) { // alleen automatisch aanpassen als slug nog niet manueel gewijzigd werd
                    return; // stop zodra gebruiker eigen slug gebruikt
                }
                $set('slug', Str::slug((string)$state)); // maak slug op basis van titel
            })->placeholder('Bijv. Laravel Filament media uitgelegd') // voorbeeldtekst
            ->helperText('Geef een duidelijke titel voor de blogpost'), // extra uitleg
            TextInput::make('slug')->label('Slug') // label van slugveld
            ->maxLength(255) // maximum lengte
            ->required() // slug moet uiteindelijk bestaan
            ->unique(ignoreRecord: true) // uniek behalve op huidig record
            ->dehydrateStateUsing(fn(?string $state, Get $get): string => filled($state) ? Str::slug($state) // maak een nette slug van manuele input
                : Str::slug((string)$get('title'))) // gebruik titel als slug leeg blijft
            ->helperText('Laat je dit leeg, dan wordt
automatisch de titel gebruikt met koppeltekens.'), // hulptekst
            Textarea::make('excerpt')->label('Samenvatting') // korte intro
            ->rows(3) // hoogte van tekstvak
            ->helperText('Korte introtekst voor
overzichtspagina’s'), // uitleg onder veld
            Textarea::make('body')->label('Inhoud') // hoofdinhoud van de post
            ->required() // inhoud is verplicht
            ->rows(12) // groter tekstvak
            ->helperText('Volledige inhoud van de blogpost'), // hulptekst
            Select::make('categories')->label('Categorieën') // label van categorieveld
            ->relationship('categories', 'name') // many-tomany relatie
            ->multiple() // meerdere categorieën mogelijk
            ->preload(), // vooraf laden van opties
            Toggle::make('is_published')->label('Gepubliceerd') // label van publicatiestatus
            ->default(false), // standaard niet gepubliceerd
            DateTimePicker::make('published_at')->label('Publicatiedatum') // datum van publicatie
            ->visible(fn(Get $get): bool => (bool)$get('is_published')), // enkel tonen als gepubliceerd aan staat
        ])->columns(2), // tweekoloms layout voor betere UX
            Section::make('Featured image') // aparte sectie voor hoofdafbeelding
            ->relationship('featuredImage') // deze velden worden opgeslagen op de MorphOne-relatie
            ->schema([FileUpload::make('file_path')->label('Afbeelding') // label van uploadveld
            ->image() // alleen afbeeldingen toelaten
            ->disk('public') // opslaan en ophalen via public disk
            ->directory('posts') // bestanden in map posts bewaren
            ->visibility('public') // publiek toegankelijk maken
            ->imageEditor() // eenvoudige afbeeldingseditor inschakelen
            ->imagePreviewHeight('250') // grotere previewhoogte zodat bestaande afbeelding duidelijk zichtbaar is
            ->panelLayout('integrated') // nettere geïntegreerde previewlayout
            ->panelAspectRatio('16:9') // mooier beeldvak voor blogafbeeldingen
            ->openable() // knop om afbeelding in nieuw tabblad te openen
            ->downloadable() // knop om afbeelding te downloaden
            ->nullable() // afbeelding is voorlopig niet verplicht
            ->helperText('Upload hier de featured image van de post. Bij het bewerken zie je hier ook de huidige afbeelding terug als preview.'), // extra uitleg
                TextInput::make('alt_text')->label('Alt-tekst') // tekst voor accessibility en SEO
                ->maxLength(255) // maximum lengte
                ->helperText('Beschrijf kort wat op de afbeelding
te zien is'), // hulptekst
                Textarea::make('caption')->label('Caption') // optioneel onderschrift
                ->rows(2) // compact tekstvak
                ->helperText('Optioneel onderschrift bij de
afbeelding'), // hulptekst
                Hidden::make('disk')->default('public'), // vaste diskwaarde automatisch invullen
                Hidden::make('is_featured')->default(true), // deze mediarecord is altijd de featured image
            ])->columns(1), // media onder elkaar tonen
        ]);
}
}
