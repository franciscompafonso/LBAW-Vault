<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    // Specify the table name
    protected $table = 'category';

    /**
     * Get the events that belong to that category.
     */
    public function events() : HasMany
    {
        return $this->hasMany(Event::class);
    }
}
