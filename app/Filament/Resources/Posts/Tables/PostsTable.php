<?php
namespace App\Filament\Resources\Posts\Tables;
use Filament\Actions\BulkActionGroup; // bulk acties groeperen
use Filament\Actions\DeleteAction; // deleteactie per rij
use Filament\Actions\EditAction; // editactie per rij
use Filament\Actions\DeleteBulkAction; // bulk delete
use Filament\Tables\Columns\IconColumn; // boolean kolom als icoon
use Filament\Tables\Columns\ImageColumn; // afbeeldingskolom
use Filament\Tables\Columns\TextColumn; // tekstkolommen
use Filament\Tables\Filters\SelectFilter; // dropdownfilters
use Filament\Tables\Table; // tabel-object
class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featuredImage.file_path')
                    ->label('Afbeelding') // toon de featured image in het overzicht
    ->disk('public') // haal afbeelding op via public disk
    ->square(), // vierkante thumbnail voor nette weergave
TextColumn::make('id')->label('ID') // record-ID
->sortable(), // sorteerbaar
                TextColumn::make('title')
                    ->label('Titel') // titel van de post
                    ->searchable() // zoekbaar maken
                    ->sortable(), // sorteerbaar maken
                TextColumn::make('slug')
                    ->label('Slug') // URL-slug
                    ->searchable() // zoekbaar maken
                    ->toggleable(), // mag verborgen worden
                TextColumn::make('user.name')
                    ->label('Auteur') // naam van auteur tonen
                    ->sortable(), // sorteerbaar
                IconColumn::make('is_published')
                    ->label('Live') // korte statusweergave
                    ->boolean(), // visuele booleanweergave
                TextColumn::make('published_at')
                    ->label('Publicatiedatum') // publicatiedatum tonen
                    ->dateTime('d/m/Y H:i') // datum en tijd formatteren
                    ->sortable(), // sorteerbaar
                TextColumn::make('created_at')
                    ->label('Aangemaakt') // aanmaakdatum
                    ->since() // relatieve tijd tonen
                    ->sortable(), // sorteerbaar
            ])
            ->filters([
                SelectFilter::make('is_published')
                    ->label('Status') // statusfilter
                    ->options([
                        1 => 'Gepubliceerd', // gepubliceerde posts
                        0 => 'Draft', // niet gepubliceerde posts
                    ]),
            ])
            ->recordActions([
                EditAction::make(), // edit knop per rij
                DeleteAction::make(), // delete knop per rij
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(), // meerdere posts tegelijk verwijderen
                ]),
            ]);
    }
}
