<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'price',
        'alltime_percent',
        'collection_id'
    ];

    public function periods()
    {
        return $this->hasMany(Period::class);
    }
}
