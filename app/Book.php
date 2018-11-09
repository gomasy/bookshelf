<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Facades\ {
    App\Libs\NDL
};

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'id', 'user_id', 'title', 'volume', 'authors', 'isbn', 'jpno', 'published_date', 'ndl_url' ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [ 'isbn10' ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_id', function (Builder $builder) {
            $builder->where('user_id', \Auth::id());
        });
    }

    public function getIsbn10Attribute()
    {
        return NDL::isbn13to10($this->isbn);
    }
}
