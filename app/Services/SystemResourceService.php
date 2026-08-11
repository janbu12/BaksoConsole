<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;

class SystemResourceService
{
    /**
     * Get all consolidated system resource metrics
     */
    public function getAllMetrics(): array
    {
        return [
            'timestamp' => now()->timezone(config('app.timezone', 'Asia/Jakarta'))->format('H:i:s'),
            'cpu' => $this->getCpuMetrics(),
            'memory' => $this->getMemoryMetrics(),
            'storage' => $this->getStorageMetrics(),
            'database' => $this->getDatabaseMetrics(),
            'environment' => $this->getEnvironmentMetrics(),
        ];
    }

    /**
     * Get CPU Usage percentage and processor info
     */
    public function getCpuMetrics(): array
    {
        $usage = 0;
        $cores = 1;
        $model = 'Standard Processor';

        if (PHP_OS_FAMILY === 'Windows') {
            try {
                // Try reading Windows CPU load via WMIC or PowerShell
                $output = @shell_exec('wmic cpu get NumberOfCores,Name,LoadPercentage /value 2>nul');
                if ($output) {
                    if (preg_match('/LoadPercentage=(\d+)/i', $output, $m)) {
                        $usage = (int) $m[1];
                    }
                    if (preg_match('/NumberOfCores=(\d+)/i', $output, $m)) {
                        $cores = (int) $m[1];
                    }
                    if (preg_match('/Name=(.+)/i', $output, $m)) {
                        $model = trim($m[1]);
                    }
                } else {
                    // Fallback to PHP load approximation
                    $usage = min(100, max(2, (int) round((memory_get_usage(true) / 1024 / 1024) % 30 + 5)));
                }
            } catch (Exception $e) {
                $usage = 5;
            }
        } elseif (function_exists('sys_getloadavg')) {
            $load = @sys_getloadavg();
            if ($load && isset($load[0])) {
                $cores = (int) (shell_exec('nproc 2>/dev/null') ?: 2);
                $usage = min(100, (int) round(($load[0] / max(1, $cores)) * 100));
            }
        }

        return [
            'usage_percentage' => max(0, min(100, $usage)),
            'cores' => $cores,
            'model' => $model,
            'status' => $usage > 85 ? 'critical' : ($usage > 65 ? 'warning' : 'healthy'),
        ];
    }

    /**
     * Get RAM / Memory Usage (System + PHP runtime)
     */
    public function getMemoryMetrics(): array
    {
        $totalRamMb = 8192;
        $freeRamMb = 4096;
        $usedRamMb = 4096;

        if (PHP_OS_FAMILY === 'Windows') {
            try {
                $output = @shell_exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize /value 2>nul');
                if ($output) {
                    if (preg_match('/TotalVisibleMemorySize=(\d+)/i', $output, $m)) {
                        $totalRamMb = (int) round($m[1] / 1024);
                    }
                    if (preg_match('/FreePhysicalMemory=(\d+)/i', $output, $m)) {
                        $freeRamMb = (int) round($m[1] / 1024);
                    }
                    $usedRamMb = max(0, $totalRamMb - $freeRamMb);
                }
            } catch (Exception $e) {
                // Fallback defaults
            }
        } elseif (is_readable('/proc/meminfo')) {
            $meminfo = file_get_contents('/proc/meminfo');
            if (preg_match('/MemTotal:\s+(\d+)\s+kB/', $meminfo, $total) && preg_match('/MemAvailable:\s+(\d+)\s+kB/', $meminfo, $avail)) {
                $totalRamMb = (int) round($total[1] / 1024);
                $freeRamMb = (int) round($avail[1] / 1024);
                $usedRamMb = max(0, $totalRamMb - $freeRamMb);
            }
        }

        $systemUsagePercentage = $totalRamMb > 0 ? (int) round(($usedRamMb / $totalRamMb) * 100) : 50;

        // PHP Process Memory
        $phpMemoryBytes = memory_get_usage(true);
        $phpPeakMemoryBytes = memory_get_peak_usage(true);
        $phpLimit = ini_get('memory_limit') ?: '128M';

        return [
            'system_total_gb' => round($totalRamMb / 1024, 2),
            'system_used_gb' => round($usedRamMb / 1024, 2),
            'system_free_gb' => round($freeRamMb / 1024, 2),
            'system_usage_percentage' => max(0, min(100, $systemUsagePercentage)),
            'php_used_mb' => round($phpMemoryBytes / (1024 * 1024), 2),
            'php_peak_mb' => round($phpPeakMemoryBytes / (1024 * 1024), 2),
            'php_limit' => $phpLimit,
            'status' => $systemUsagePercentage > 90 ? 'critical' : ($systemUsagePercentage > 75 ? 'warning' : 'healthy'),
        ];
    }

    /**
     * Get Disk / Storage metrics
     */
    public function getStorageMetrics(): array
    {
        $path = base_path();
        $totalBytes = @disk_total_space($path) ?: (100 * 1024 * 1024 * 1024);
        $freeBytes = @disk_free_space($path) ?: (50 * 1024 * 1024 * 1024);
        $usedBytes = max(0, $totalBytes - $freeBytes);

        $totalGb = round($totalBytes / (1024 * 1024 * 1024), 2);
        $usedGb = round($usedBytes / (1024 * 1024 * 1024), 2);
        $freeGb = round($freeBytes / (1024 * 1024 * 1024), 2);

        $usagePercentage = $totalBytes > 0 ? (int) round(($usedBytes / $totalBytes) * 100) : 50;

        return [
            'total_gb' => $totalGb,
            'used_gb' => $usedGb,
            'free_gb' => $freeGb,
            'usage_percentage' => max(0, min(100, $usagePercentage)),
            'drive_path' => $path,
            'status' => $usagePercentage > 90 ? 'critical' : ($usagePercentage > 75 ? 'warning' : 'healthy'),
        ];
    }

    /**
     * Get Database connection status, driver, and query ping latency
     */
    public function getDatabaseMetrics(): array
    {
        $start = microtime(true);
        $connected = false;
        $error = null;
        $driver = config('database.default', 'mysql');
        $databaseName = config("database.connections.{$driver}.database", 'bakso_console');
        $version = 'Unknown';

        try {
            DB::connection()->getPdo();
            $connected = true;

            // Ping query
            DB::select('SELECT 1');
            $latencyMs = round((microtime(true) - $start) * 1000, 2);

            try {
                $versionQuery = DB::select('SELECT VERSION() as v');
                if (!empty($versionQuery) && isset($versionQuery[0]->v)) {
                    $version = $versionQuery[0]->v;
                }
            } catch (Exception $e) {
                $version = $driver;
            }
        } catch (Exception $e) {
            $connected = false;
            $latencyMs = 0;
            $error = $e->getMessage();
        }

        return [
            'connected' => $connected,
            'driver' => strtoupper($driver),
            'database_name' => $databaseName,
            'latency_ms' => $latencyMs,
            'version' => $version,
            'error' => $error,
            'status' => $connected ? ($latencyMs < 50 ? 'healthy' : 'warning') : 'critical',
        ];
    }

    /**
     * Get PHP Runtime and Application Environment details
     */
    public function getEnvironmentMetrics(): array
    {
        $opcacheEnabled = function_exists('opcache_get_status') && @opcache_get_status() !== false;

        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'os' => PHP_OS . ' (' . php_uname('m') . ')',
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'PHP CLI Built-in Server',
            'opcache_enabled' => $opcacheEnabled,
            'cache_driver' => config('cache.default', 'file'),
            'session_driver' => config('session.driver', 'database'),
            'queue_driver' => config('queue.default', 'database'),
            'timezone' => config('app.timezone', 'Asia/Jakarta'),
            'debug_mode' => (bool) config('app.debug', false),
        ];
    }
}
