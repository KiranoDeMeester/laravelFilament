<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Post extends Model
{
    protected $fillable = [
        'user_id', // auteur mag mass assigned worden
        'title', // titel mag mass assigned worden
        'slug', // slug mag mass assigned worden
        'excerpt', // samenvatting mag mass assigned worden
        'body', // inhoud mag mass assigned worden
        'is_published', // publicatiestatus mag mass assigned worden
        'published_at', // publicatiedatum mag mass assigned worden
    ];
    public function user()
    {
        return $this->belongsTo(User::class); // elke post hoort bij 1 user
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class); // een post heeft meerdere categorieën
}
}
