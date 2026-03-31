<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // relatie naar auteur
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // many-to-many met categories
use Illuminate\Database\Eloquent\Relations\MorphMany; // meerdere media mogelijk
use Illuminate\Database\Eloquent\Relations\MorphOne; // 1 featured image
class Post extends Model
{
    protected $fillable = [
        'user_id', // auteur van de post
        'title', // titel van de post
        'slug', // unieke slug
        'excerpt', // korte samenvatting
        'body', // volledige inhoud
        'is_published', // publicatiestatus
        'published_at', // publicatiedatum
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // elke post hoort bij 1 user
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class); // een post heeft meerdere categorieën
}
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable'); // alle media van deze post
}
    public function featuredImage(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable') // 1 featured image
        ->where('is_featured', true); // filter enkel op featured media
    }
}
