<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'username', 'email', 'phone', 'password',
        'avatar', 'bio', 'status', 'referral_code', 'referred_by',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->referral_code ??= strtoupper(Str::random(8));
        });

        static::created(function (User $user) {
            // Setiap user otomatis punya wallet
            $user->wallet()->create();
        });
    }

    // ---- Relasi ----
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class, 'creator_id');
    }

    public function unlocks(): HasMany
    {
        return $this->hasMany(Unlock::class);
    }

    public function royalties(): HasMany
    {
        return $this->hasMany(Royalty::class, 'creator_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class, 'affiliate_id');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function following(): HasMany
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    /** Akses ke panel admin Filament */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->hasAnyRole(['admin', 'operator']);
    }
}
