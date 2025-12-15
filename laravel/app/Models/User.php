<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Available user roles
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_DISABLED_USER = 'disabled_user';
    public const ROLE_FAMILY_USER = 'family_user';
    public const ROLE_SUPPORTER_USER = 'supporter_user';

    public const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_DISABLED_USER => 'Disabled User',
        self::ROLE_FAMILY_USER => 'Family Disabled',
        self::ROLE_SUPPORTER_USER => 'Supporter',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'wallet_balance',
        'points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
        ];
    }

    /**
     * Determine if the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Admin panel - only admins
        if ($panel->getId() === 'admin') {
            return $this->isAdmin();
        }
        
        // User panel - all authenticated users
        return true;
    }

    // Role Check Methods

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isDisabledUser(): bool
    {
        return $this->role === self::ROLE_DISABLED_USER;
    }

    public function isFamilyUser(): bool
    {
        return $this->role === self::ROLE_FAMILY_USER;
    }

    public function isSupporterUser(): bool
    {
        return $this->role === self::ROLE_SUPPORTER_USER;
    }

    /**
     * Check if user is a premium/supporter type
     */
    public function isPremium(): bool
    {
        return $this->role === self::ROLE_SUPPORTER_USER;
    }

    // Relationships

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    // Helpers

    public function getTotalIncomeAttribute(): float
    {
        return $this->incomes()->sum('amount');
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }
}

