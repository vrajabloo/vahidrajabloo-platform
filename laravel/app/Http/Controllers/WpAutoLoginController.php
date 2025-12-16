<?php

namespace App\Http\Controllers;

use App\Models\WpLoginToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WpAutoLoginController extends Controller
{
    /**
     * Generate token and redirect to WordPress auto-login
     */
    public function redirectToWordPress(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Only admins can access WordPress dashboard');
        }

        // WordPress admin username (must match the WP user)
        $wpUsername = 'admin';
        
        // Generate secure token
        $loginToken = WpLoginToken::generateToken(
            userEmail: $user->email,
            wpUsername: $wpUsername,
            expiryMinutes: 5
        );

        // Redirect to WordPress auto-login endpoint
        $wpAutoLoginUrl = config('app.wordpress_url', 'https://vahidrajabloo.com') 
            . '/wp-auto-login.php?token=' . $loginToken->token;

        return redirect()->away($wpAutoLoginUrl);
    }

    /**
     * API endpoint for WordPress to validate token
     */
    public function validateToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:64',
        ]);

        $loginToken = WpLoginToken::validateToken($request->token);

        if (!$loginToken) {
            return response()->json([
                'valid' => false,
                'error' => 'Invalid or expired token',
            ], 401);
        }

        return response()->json([
            'valid' => true,
            'wp_username' => $loginToken->wp_username,
            'email' => $loginToken->user_email,
        ]);
    }
}
