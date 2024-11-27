<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    // Disable timestamps if you're manually handling created_at, updated_at
    public $timestamps = false;

    // Define the table name if it's not automatically inferred
    protected $table = 'password_reset_tokens';

    // Define the fillable columns
    protected $fillable = ['email', 'token', 'created_at', 'expires_at'];
}
