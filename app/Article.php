<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Article extends Model
{
    protected $fillable = ['title', 'body', 'image', 'subtitle'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getImageAttribute($image)
    {
        return $image ? asset('storage/images/' . $image) : null;
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('M d, Y');
    }
}
