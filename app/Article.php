<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = ['title', 'body'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::saving(function ($article) {
            $article->excerpt = Str::limit($article->body, 300);
        });
    }
}
