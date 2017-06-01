<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'id', 'user_id', 'title', 'title_ruby', 'volume', 'authors', 'isbn', 'jpno', 'published_date', 'ndl_url' ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_id', function(Builder $builder) {
            $builder->where('user_id', Auth::id());
        });
    }

    public static function formatter(array $book)
    {
        return array_merge([
            'id' => Auth::user()['next_id'],
            'user_id' => Auth::id(),
        ], $book);
    }

    public function scopeCurrentUser(Builder $query)
    {
        return $query->get([ 'id', 'title', 'title_ruby', 'volume', 'authors', 'isbn', 'jpno', 'published_date', 'ndl_url' ]);
    }

    public function scopeSearch(Builder $query, $id)
    {
        return $query->where('id', $id);
    }
}
