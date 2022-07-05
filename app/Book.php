<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isbn',
        'title',
        'description',
    ];

    protected $appends = [
        'review',
    ];


    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }

    public function getReviewAttribute()
    {
        return $this->review = [
            'count' =>  $this->reviews()->count(),
            'avg' => $this->reviews()->avg('review') ? $this->reviews()->avg('review') : 0
        ];
    }
}
