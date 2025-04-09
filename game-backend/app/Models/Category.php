<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }
}
