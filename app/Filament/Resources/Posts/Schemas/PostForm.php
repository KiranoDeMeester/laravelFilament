<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([Select::make('user_id')->label('Auteur') // label in het formulier
        ->relationship('user', 'name') // gebruik de user-relatie en toon de naam
        ->required() // verplicht veld
        ->searchable() // zoekfunctie in de dropdown
        ->preload(), // opties vooraf laden
            TextInput::make('title')->label('Titel') // label van het veld
            ->required() // titel is verplicht
            ->minLength(3) // minimum 3 karakters
            ->maxLength(255) // maximum 255 karakters
            ->live(onBlur: true) // reageer wanneer de gebruiker uit het veld klikt
            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                if (($get('slug') ?? '') !== Str::slug((string)$old)) { // enkel updaten als slug nog automatisch was
                    return; // stop als gebruiker de slug al manueel aangepast heeft
                }
                $set('slug', Str::slug((string)$state)); // maak slug op basis van de nieuwe titel
            })->placeholder('Bijv. Laravel Filament voor beginners') //voorbeeldtekst in het veld
            ->helperText('Geef een duidelijke titel voor de
                    blogpost'), // extra uitleg onder het veld
            TextInput::make('slug')->label('Slug') // label van het slugveld
            ->maxLength(255) // maximum 255 karakters
            ->required() // uiteindelijk moet er een slug opgeslagen worden
            ->unique(ignoreRecord: true) // slug moet uniek zijn, behalve op het huidige record bij edit
            ->dehydrateStateUsing(fn(?string $state, Get $get): string => filled($state) ? Str::slug($state) // als gebruiker iets invulde, maak daar een nette slug van
                : Str::slug((string)$get('title'))) // anders maak slug van de titel
            ->helperText('Je mag zelf een slug invullen. Laat je dit
                    leeg, dan wordt automatisch de titel gebruikt met koppeltekens.'), // uitleg voor de gebruiker
            Textarea::make('excerpt')->label('Samenvatting') // label van samenvatting
            ->rows(3) // hoogte van het tekstvak
            ->helperText('Korte introtekst voor overzichtspagina’s'), // uitleg onder het veld
            Textarea::make('body')->label('Inhoud') // label van de inhoud
            ->required() // inhoud is verplicht
            ->rows(12) // groter tekstvak voor content
            ->helperText('Volledige inhoud van de blogpost'), // hulptekst
            Select::make('categories')->label('Categorieën') // label van de relatie
            ->relationship('categories', 'name') // koppel aan categories-relatie en toon naam
            ->multiple() // meerdere categorieën selecteerbaar
            ->preload(), // opties vooraf laden
            Toggle::make('is_published')->label('Gepubliceerd') // label van boolean veld
            ->default(false), // standaard niet gepubliceerd
            DateTimePicker::make('published_at')->label('Publicatiedatum') // label van datumveld
            ->visible(fn(Get $get): bool => (bool)$get('is_published')), // enkel tonen als gepubliceerd aan staat
        ]);
    }
}
