<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (User $user) {
            $user->name = trim($user->first_name . ' ' . $user->last_name);
        });
    }

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the outgoing documents created by this user.
     */
    public function outgoingDocuments(): HasMany
    {
        return $this->hasMany(OutgoingDocument::class, 'created_by');
    }

    /**
     * Get the certificates created by this user.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'created_by');
    }

    /**
     * Get the navy news created by this user.
     */
    public function navyNews(): HasMany
    {
        return $this->hasMany(NavyNews::class, 'created_by');
    }

    /**
     * Get the school orders created by this user.
     */
    public function schoolOrders(): HasMany
    {
        return $this->hasMany(SchoolOrder::class, 'created_by');
    }

    /**
     * Get the special orders created by this user.
     */
    public function specialOrders(): HasMany
    {
        return $this->hasMany(SpecialOrder::class, 'created_by');
    }

    /**
     * Get the activities created by this user.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'created_by');
    }

    /**
     * Get the personnel records created by this user.
     */
    public function personnel(): HasMany
    {
        return $this->hasMany(Personnel::class, 'created_by');
    }
}

