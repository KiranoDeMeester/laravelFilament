<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model; // basis Eloquent model
use Illuminate\Database\Eloquent\Relations\MorphTo; // inverse polymorfe relatie
use Illuminate\Database\Eloquent\SoftDeletes; // juiste SoftDeletes trait import
class Media extends Model
{
    use SoftDeletes; // verwijder records niet meteen definitief
    protected $fillable = [
        'disk', // storage disk waarop het bestand staat
        'file_name', // bestandsnaam
        'file_path', // opslagpad
        'mime_type', // mime type van het bestand
        'file_size', // bestandsgrootte in bytes
        'alt_text', // alternatieve tekst
        'caption', // optioneel onderschrift
        'sort_order', // sorteervolgorde
        'is_featured', // aanduiding featured image
    ];
    public function mediable(): MorphTo
    {
        return $this->morphTo(); // inverse koppeling naar Post, User, Category, ...
}
}
