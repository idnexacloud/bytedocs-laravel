<?php

namespace ByteDocs\Laravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ManageBansCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'bytedocs:bans 
                            {action : Action to perform (list|unblock|clear|block)}
                            {ip? : IP address to manage (required for unblock/block)}
                            {--duration=60 : Ban duration in minutes for block action}';

    /**
     * The console command description.
     */
    protected $description = 'Manage IP bans for ByteDocs authentication';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action');
        $ip = $this->argument('ip');

        match ($action) {
            'list' => $this->listBans(),
            'unblock' => $this->unblockIp($ip),
            'clear' => $this->clearAllBans(),
            'block' => $this->blockIp($ip),
            default => $this->error("Invalid action. Use: list, unblock, clear, or block")
        };

        return self::SUCCESS;
    }

    /**
     * List all currently banned IPs
     */
    protected function listBans(): void
    {
        $bannedIps = $this->getBannedIps();
        
        if (empty($bannedIps)) {
            $this->info('No IPs are currently banned.');
            return;
        }

        $this->info('Currently Banned IPs:');

        $headers = ['IP Address', 'Status', 'Failed Attempts', 'Action'];
        $rows = [];

        foreach ($bannedIps as $ip) {
            $banKey = "bytedocs_ban_{$ip}";
            $attemptsKey = "bytedocs_attempts_{$ip}";
            
            $isBanned = Cache::has($banKey);
            $attempts = Cache::get($attemptsKey, 0);
            
            if ($isBanned) {
                $rows[] = [
                    $ip,
                    '<fg=red>BANNED</fg=red>',
                    $attempts,
                    'php artisan bytedocs:bans unblock ' . $ip
                ];
            }
        }

        if (empty($rows)) {
            $this->info('No IPs are currently banned.');
            return;
        }

        $this->table($headers, $rows);
        $this->newLine();
        $this->info('Use "php artisan bytedocs:bans unblock <ip>" to unblock an IP');
    }

    /**
     * Unblock a specific IP
     */
    protected function unblockIp(?string $ip): void
    {
        if (!$ip) {
            $this->error('IP address is required for unblock action.');
            $this->info('Usage: php artisan bytedocs:bans unblock 192.168.1.100');
            return;
        }

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->error("Invalid IP address: {$ip}");
            return;
        }

        $banKey = "bytedocs_ban_{$ip}";
        $attemptsKey = "bytedocs_attempts_{$ip}";

        $wasBanned = Cache::has($banKey);
        
        // Remove ban and attempts
        Cache::forget($banKey);
        Cache::forget($attemptsKey);

        if ($wasBanned) {
            $this->info("Successfully unblocked IP: {$ip}");
        } else {
            $this->warn("IP {$ip} was not banned, but cleared any stored attempts.");
        }
    }

    /**
     * Clear all bans
     */
    protected function clearAllBans(): void
    {
        if (!$this->confirm('Are you sure you want to clear ALL IP bans?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $bannedIps = $this->getBannedIps();
        $cleared = 0;

        foreach ($bannedIps as $ip) {
            $banKey = "bytedocs_ban_{$ip}";
            $attemptsKey = "bytedocs_attempts_{$ip}";
            
            if (Cache::has($banKey) || Cache::has($attemptsKey)) {
                Cache::forget($banKey);
                Cache::forget($attemptsKey);
                $cleared++;
            }
        }

        if ($cleared > 0) {
            $this->info("Cleared {$cleared} IP bans and attempt counters.");
        } else {
            $this->info('No bans found to clear.');
        }
    }

    /**
     * Block a specific IP
     */
    protected function blockIp(?string $ip): void
    {
        if (!$ip) {
            $this->error('IP address is required for block action.');
            $this->info('Usage: php artisan bytedocs:bans block 192.168.1.100');
            return;
        }

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->error("Invalid IP address: {$ip}");
            return;
        }

        // Check if IP is whitelisted
        $whitelistIps = config('bytedocs.auth.admin.whitelist_ips', ['127.0.0.1']);
        if (in_array($ip, $whitelistIps)) {
            $this->error("Cannot block whitelisted IP: {$ip}");
            $this->info('Whitelisted IPs: ' . implode(', ', $whitelistIps));
            return;
        }

        $duration = $this->option('duration');
        $banKey = "bytedocs_ban_{$ip}";
        
        if (Cache::has($banKey)) {
            $this->warn("IP {$ip} is already banned.");
            return;
        }

        // Block the IP
        Cache::put($banKey, true, now()->addMinutes($duration));
        
        $this->info("Successfully blocked IP: {$ip} for {$duration} minutes");
        $this->info("Expires at: " . now()->addMinutes($duration)->format('Y-m-d H:i:s'));
    }

    /**
     * Get list of potentially banned IPs (simplified version)
     */
    protected function getBannedIps(): array
    {
        // This is a simplified approach - in production, you might want to
        // store a list of IPs being tracked or scan cache keys more efficiently
        $ips = [];
        
        // Common IPs to check (you could enhance this)
        $commonIps = [
            '127.0.0.1', '::1', // Localhost
            request()->ip(), // Current request IP if available
        ];

        // Add any additional IPs from environment or config
        $additionalIps = config('bytedocs.auth.admin.whitelist_ips', []);
        $commonIps = array_merge($commonIps, $additionalIps);

        foreach (array_unique($commonIps) as $ip) {
            if ($ip && (Cache::has("bytedocs_ban_{$ip}") || Cache::has("bytedocs_attempts_{$ip}"))) {
                $ips[] = $ip;
            }
        }

        return array_unique($ips);
    }
}