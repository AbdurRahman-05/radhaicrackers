<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'otp',
        'otp_expires_at',
        'last_otp_verified_at',
        'is_admin',
        'is_active',
        'remember_token',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'otp',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
            'last_otp_verified_at' => 'datetime',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Order::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function getDisplayNameAttribute()
    {
        if (empty($this->name) || preg_match('/^User-\d+$/', $this->name)) {
            $latestOrder = $this->orders()->whereNotNull('customer_name')->where('customer_name', '!=', '')->latest()->first();
            if ($latestOrder) {
                $realName = $latestOrder->customer_name;
                $this->name = $realName;
                $this->saveQuietly();
                return $realName;
            }
            return str_replace('User-', '', $this->name);
        }
        return $this->name;
    }
}
