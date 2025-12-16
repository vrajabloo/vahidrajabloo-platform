<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WpLoginToken extends Model
{
    protected $fillable = [
        'token',
        'user_email',
        'wp_username',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Generate a new token for WordPress auto-login
     */
    public static function generateToken(string $userEmail, string $wpUsername = 'admin', int $expiryMinutes = 5): self
    {
        // Clean up expired tokens
        self::where('expires_at', '<', now())->delete();
        
        return self::create([
            'token' => Str::random(64),
            'user_email' => $userEmail,
            'wp_username' => $wpUsername,
            'expires_at' => now()->addMinutes($expiryMinutes),
            'used' => false,
        ]);
    }

    /**
     * Validate and mark token as used
     */
    public static function validateToken(string $token): ?self
    {
        $loginToken = self::where('token', $token)
            ->where('expires_at', '>', now())
            ->where('used', false)
            ->first();

        if ($loginToken) {
            $loginToken->update(['used' => true]);
        }

        return $loginToken;
    }

    /**
     * Check if token is valid (for API)
     */
    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }
}
