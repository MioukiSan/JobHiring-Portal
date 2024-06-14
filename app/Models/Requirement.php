<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'csc_form',
        'tor_diploma',
        'training_cert',
        'eligibility',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
