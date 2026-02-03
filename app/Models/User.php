<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model
 * 
 * Represents a user in the system.
 * 
 * @property int $id
 * @property string $name
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property \Carbon\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Collection<OutgoingDocument> $outgoingDocuments
 * @property-read Collection<Certificate> $certificates
 * @property-read Collection<NavyNews> $navyNews
 * @property-read Collection<SchoolOrder> $schoolOrders
 * @property-read Collection<SpecialOrder> $specialOrders
 * @property-read Collection<Activity> $activities
 * @property-read Collection<Personnel> $personnel
 * @property-read string $full_name
 */
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
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (User $user): void {
            $user->name = trim("{$user->first_name} {$user->last_name}");
        });
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

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

    /**
     * Get the vehicle bookings made by this user.
     */
    public function vehicleBookings(): HasMany
    {
        return $this->hasMany(VehicleBooking::class, 'user_id');
    }

    /**
     * Check if the user has verified their email.
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }
}
