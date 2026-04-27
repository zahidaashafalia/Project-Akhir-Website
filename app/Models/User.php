<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'avatar',
        'role', 'balance', 'points', 'referral_code',
        'referred_by', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'owner_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function favorites()
    {
        return $this->belongsToMany(Restaurant::class, 'favorites')->withTimestamps();
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    // Helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRestaurantOwner(): bool
    {
        return $this->role === 'restaurant_owner';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=FF6B35&color=fff';
    }

    public function addPoints(int $points, string $reason = ''): void
    {
        $this->increment('points', $points);
    }

    public function deductPoints(int $points): bool
    {
        if ($this->points < $points) return false;
        $this->decrement('points', $points);
        return true;
    }

    public function addBalance(float $amount): void
    {
        $this->increment('balance', $amount);
        WalletTransaction::create([
            'user_id' => $this->id,
            'type' => 'credit',
            'amount' => $amount,
            'balance_after' => $this->fresh()->balance,
            'description' => 'Penambahan saldo',
        ]);
    }

    public function deductBalance(float $amount): bool
    {
        if ($this->balance < $amount) return false;
        $this->decrement('balance', $amount);
        WalletTransaction::create([
            'user_id' => $this->id,
            'type' => 'debit',
            'amount' => $amount,
            'balance_after' => $this->fresh()->balance,
            'description' => 'Pembayaran pesanan',
        ]);
        return true;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->referral_code = strtoupper(substr(md5(uniqid()), 0, 8));
        });
    }
}