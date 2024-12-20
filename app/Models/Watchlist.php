<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $table = 'watchlist';

    protected $fillable = [
        'user_id',
        'item_id',
        'item_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
