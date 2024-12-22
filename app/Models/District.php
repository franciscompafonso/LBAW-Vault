<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    // Specify the table name
    protected $table = 'district';

    /**
     * Get the events that belong to that district.
     */
    public function events() : HasMany
    {
        return $this->hasMany(Event::class);
    }

}
