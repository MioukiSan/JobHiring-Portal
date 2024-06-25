<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'acc_verified_at' => 'datetime',
            'password' => 'hashed',
            'accont_status' => 'string',
        ];
    }
    
    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }

    public function requirement()
    {
        return $this->hasOne(Requirement::class);
    }

    public function salaryGrade()
    {
        return $this->hasMany(SalaryGrade::class);
    }
}
