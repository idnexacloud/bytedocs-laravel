<?php

namespace ByteDocs\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class DocsAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if auth is enabled
        if (!config('bytedocs.auth.enabled', false)) {
            return $next($request);
        }

        // Validate that password is configured
        $configuredPassword = config('bytedocs.auth.password');
        if (empty($configuredPassword)) {
            return response()->view('bytedocs::auth.config-error', [
                'error_title' => 'Authentication Not Configured',
                'error_message' => 'ByteDocs authentication is enabled but no password is configured.',
                'error_details' => [
                    'Please set BYTEDOCS_AUTH_PASSWORD in your .env file',
                    'Or disable authentication by setting BYTEDOCS_AUTH_ENABLED=false',
                    'Check your configuration at config/bytedocs.php'
                ]
            ], 500);
        }

        $ip = $request->ip();
        $sessionKey = 'bytedocs_authenticated';
        $banKey = "bytedocs_ban_{$ip}";
        $attemptsKey = "bytedocs_attempts_{$ip}";

        // Check if IP is banned
        if ($this->isIpBanned($ip)) {
            return response()->view('bytedocs::auth.banned', [], 403);
        }

        // Check if already authenticated and session is valid
        if ($this->isAuthenticated()) {
            return $next($request);
        }

        // Handle login form submission
        if ($request->isMethod('POST') && $request->has('password')) {
            return $this->handleLogin($request, $next);
        }

        // Show login form
        return response()->view('bytedocs::auth.login', [
            'error' => $request->session()->get('bytedocs_auth_error')
        ]);
    }

    protected function isIpBanned(string $ip): bool
    {
        if (!config('bytedocs.auth.ip_ban.enabled', true)) {
            return false;
        }

        // Check if IP is whitelisted (admin IPs cannot be banned)
        $whitelistIps = config('bytedocs.auth.admin.whitelist_ips', ['127.0.0.1']);
        if (in_array($ip, $whitelistIps)) {
            return false;
        }

        $banKey = "bytedocs_ban_{$ip}";
        return Cache::has($banKey);
    }

    protected function isAuthenticated(): bool
    {
        $sessionKey = 'bytedocs_authenticated';
        $authenticatedAt = Session::get($sessionKey);
        
        if (!$authenticatedAt) {
            return false;
        }

        // Check session expiration
        $sessionExpire = config('bytedocs.auth.session_expire', 1440);
        $expirationTime = $authenticatedAt + ($sessionExpire * 60);
        
        if (time() > $expirationTime) {
            Session::forget($sessionKey);
            return false;
        }

        return true;
    }

    protected function handleLogin(Request $request, Closure $next): Response
    {
        $password = $request->input('password');
        $correctPassword = config('bytedocs.auth.password');
        $ip = $request->ip();
        $attemptsKey = "bytedocs_attempts_{$ip}";
        $banKey = "bytedocs_ban_{$ip}";

        // Check password
        if ($password === $correctPassword) {
            // Success - clear attempts and set session
            Cache::forget($attemptsKey);
            Session::put('bytedocs_authenticated', time());
            Session::forget('bytedocs_auth_error');
            
            return $next($request);
        }

        // Failed login - increment attempts
        $attempts = Cache::get($attemptsKey, 0) + 1;
        $maxAttempts = config('bytedocs.auth.ip_ban.max_attempts', 5);
        
        Cache::put($attemptsKey, $attempts, now()->addHours(24));

        // Ban IP if max attempts reached (unless whitelisted)
        if ($attempts >= $maxAttempts && config('bytedocs.auth.ip_ban.enabled', true)) {
            $whitelistIps = config('bytedocs.auth.admin.whitelist_ips', ['127.0.0.1']);
            
            if (!in_array($ip, $whitelistIps)) {
                $banDuration = config('bytedocs.auth.ip_ban.ban_duration', 60);
                Cache::put($banKey, true, now()->addMinutes($banDuration));
                Cache::forget($attemptsKey);
                
                return response()->view('bytedocs::auth.banned', [], 403);
            }
            // If IP is whitelisted, just reset attempts instead of banning
            Cache::forget($attemptsKey);
        }

        // Show error
        $remainingAttempts = $maxAttempts - $attempts;
        $errorMessage = "Password salah. Sisa percobaan: {$remainingAttempts}";
        
        Session::flash('bytedocs_auth_error', $errorMessage);
        
        return response()->view('bytedocs::auth.login', [
            'error' => $errorMessage
        ]);
    }
}