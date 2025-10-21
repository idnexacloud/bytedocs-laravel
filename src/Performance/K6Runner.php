<?php

namespace ByteDocs\Laravel\Performance;

use Exception;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class K6Runner
{
    protected string $os;
    protected string $k6Command;
    protected ?string $customK6Path;

    public function __construct(?string $customK6Path = null)
    {
        $this->customK6Path = $customK6Path;
        $this->detectOS();
        $this->validateK6Installation();
    }

    /**
     * Detect the operating system
     */
    protected function detectOS(): void
    {
        $this->os = strtolower(PHP_OS_FAMILY);
    }

    /**
     * Validate k6 is installed on the system
     */
    protected function validateK6Installation(): bool
    {
        // Check custom path first if provided
        if ($this->customK6Path) {
            if (file_exists($this->customK6Path)) {
                $this->k6Command = $this->customK6Path;
                return true;
            } else {
                throw new Exception("Custom k6 path not found: {$this->customK6Path}\n\nPlease verify the path and try again.");
            }
        }

        // Try to find k6 using standard method
        $checkCommand = $this->os === 'windows' ? 'where k6' : 'which k6';

        $process = Process::fromShellCommandline($checkCommand);
        $process->run();

        if ($process->isSuccessful()) {
            $this->k6Command = trim($process->getOutput());
            return true;
        }

        // If not found, try common installation paths (Windows)
        if ($this->os === 'windows') {
            $commonPaths = [
                'C:\ProgramData\chocolatey\bin\k6.exe',
                'C:\Program Files\k6\k6.exe',
                'C:\Program Files (x86)\k6\k6.exe',
                getenv('USERPROFILE') . '\scoop\apps\k6\current\k6.exe',
                getenv('LOCALAPPDATA') . '\Microsoft\WinGet\Packages\k6\k6.exe',
            ];

            foreach ($commonPaths as $path) {
                if (file_exists($path)) {
                    $this->k6Command = $path;
                    return true;
                }
            }
        }

        // If still not found on macOS/Linux, try common paths
        if ($this->os === 'darwin') {
            $commonPaths = [
                '/usr/local/bin/k6',
                '/opt/homebrew/bin/k6',
                '/usr/bin/k6',
            ];

            foreach ($commonPaths as $path) {
                if (file_exists($path)) {
                    $this->k6Command = $path;
                    return true;
                }
            }
        }

        if ($this->os === 'linux') {
            $commonPaths = [
                '/usr/local/bin/k6',
                '/usr/bin/k6',
                '/snap/bin/k6',
            ];

            foreach ($commonPaths as $path) {
                if (file_exists($path)) {
                    $this->k6Command = $path;
                    return true;
                }
            }
        }

        // Not found anywhere
        $this->throwInstallationError();
    }

    /**
     * Throw OS-specific installation error
     */
    protected function throwInstallationError(): void
    {
        $message = "k6 is not installed or not found on your system.\n\n";

        if ($this->os === 'windows') {
            $message .= "Searched in the following locations:\n";
            $message .= "- System PATH\n";
            $message .= "- C:\\ProgramData\\chocolatey\\bin\\k6.exe\n";
            $message .= "- C:\\Program Files\\k6\\k6.exe\n";
            $message .= "- C:\\Program Files (x86)\\k6\\k6.exe\n";
            $message .= "- " . getenv('USERPROFILE') . "\\scoop\\apps\\k6\\current\\k6.exe\n";
            $message .= "- " . getenv('LOCALAPPDATA') . "\\Microsoft\\WinGet\\Packages\\k6\\k6.exe\n\n";

            $message .= "Installation options for Windows:\n\n";
            $message .= "1. Using Chocolatey (Recommended):\n";
            $message .= "   choco install k6\n\n";
            $message .= "2. Using Scoop:\n";
            $message .= "   scoop install k6\n\n";
            $message .= "3. Using winget:\n";
            $message .= "   winget install k6 --source winget\n\n";
            $message .= "4. Download manually:\n";
            $message .= "   - Visit: https://github.com/grafana/k6/releases\n";
            $message .= "   - Download k6-vX.X.X-windows-amd64.zip\n";
            $message .= "   - Extract and add to PATH\n\n";
            $message .= "After installation, verify by running 'k6 version' in Command Prompt.\n";
            $message .= "If k6 is already installed, ensure it's in your system PATH or restart your web server.\n";
        } elseif ($this->os === 'darwin') {
            $message .= "Installation options for macOS:\n\n";
            $message .= "1. Using Homebrew (Recommended):\n";
            $message .= "   brew install k6\n\n";
            $message .= "2. Using MacPorts:\n";
            $message .= "   sudo port install k6\n\n";
            $message .= "3. Download manually:\n";
            $message .= "   Visit: https://github.com/grafana/k6/releases\n";
        } elseif ($this->os === 'linux') {
            $message .= "Installation options for Linux:\n\n";
            $message .= "1. Debian/Ubuntu:\n";
            $message .= "   sudo gpg -k\n";
            $message .= "   sudo gpg --no-default-keyring --keyring /usr/share/keyrings/k6-archive-keyring.gpg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C5AD17C747E3415A3642D57D77C6C491D6AC1D69\n";
            $message .= "   echo \"deb [signed-by=/usr/share/keyrings/k6-archive-keyring.gpg] https://dl.k6.io/deb stable main\" | sudo tee /etc/apt/sources.list.d/k6.list\n";
            $message .= "   sudo apt-get update\n";
            $message .= "   sudo apt-get install k6\n\n";
            $message .= "2. Fedora/CentOS:\n";
            $message .= "   sudo dnf install https://dl.k6.io/rpm/repo.rpm\n";
            $message .= "   sudo dnf install k6\n\n";
            $message .= "3. Using Snap:\n";
            $message .= "   sudo snap install k6\n";
        } else {
            $message .= "Please visit: https://k6.io/docs/get-started/installation/\n";
            $message .= "for installation instructions for your operating system.";
        }

        $message .= "\n\nFor more information, visit: https://k6.io/docs/get-started/installation/";

        throw new Exception($message);
    }

    /**
     * Run k6 performance test
     *
     * @param array $config Test configuration
     * @return array Test results
     */
    public function runTest(array $config): array
    {
        $scriptPath = $this->generateScript($config);

        try {
            $command = $this->buildCommand($scriptPath);

            $process = Process::fromShellCommandline($command);
            $process->setTimeout(3600); // 1 hour timeout

            $output = '';
            $errorOutput = '';
            $process->run(function ($type, $buffer) use (&$output, &$errorOutput) {
                if ($type === Process::ERR) {
                    $errorOutput .= $buffer;
                } else {
                    $output .= $buffer;
                }
            });

            // Clean up script file
            if (file_exists($scriptPath)) {
                unlink($scriptPath);
            }

            if (!$process->isSuccessful()) {
                $this->handleTestFailure($process, $output, $errorOutput);
            }

            return $this->parseOutput($output);

        } catch (ProcessFailedException $e) {
            // Clean up script file on error
            if (isset($scriptPath) && file_exists($scriptPath)) {
                unlink($scriptPath);
            }
            throw $e;
        } catch (Exception $e) {
            // Clean up script file on error
            if (isset($scriptPath) && file_exists($scriptPath)) {
                unlink($scriptPath);
            }

            throw new Exception($this->formatErrorMessage($e->getMessage()));
        }
    }

    /**
     * Handle test failure with OS-specific error messages
     */
    protected function handleTestFailure(Process $process, string $output, string $errorOutput): void
    {
        $exitCode = $process->getExitCode();
        $errorMessage = "k6 test failed";

        // Check for common errors
        if (strpos($errorOutput, 'ECONNREFUSED') !== false || strpos($output, 'ECONNREFUSED') !== false) {
            $errorMessage = "Connection refused. The endpoint URL might be incorrect or the server is not running.";
        } elseif (strpos($errorOutput, 'ENOTFOUND') !== false || strpos($output, 'ENOTFOUND') !== false) {
            $errorMessage = "Host not found. Please check the URL/domain name.";
        } elseif (strpos($errorOutput, 'ETIMEDOUT') !== false || strpos($output, 'ETIMEDOUT') !== false) {
            $errorMessage = "Connection timeout. The server is not responding.";
        } elseif (strpos($errorOutput, 'certificate') !== false || strpos($output, 'certificate') !== false) {
            $errorMessage = "SSL/TLS certificate error. Try using HTTP instead of HTTPS for local testing.";
        } elseif ($exitCode === 127) {
            $errorMessage = "k6 command not found. Please ensure k6 is installed and in your PATH.";
        } elseif ($exitCode === 1) {
            // Parse k6 specific errors
            if (!empty($errorOutput)) {
                $errorMessage = "k6 execution error:\n" . $errorOutput;
            } elseif (strpos($output, 'error') !== false) {
                $errorMessage = "k6 execution error. Check the test configuration.";
            }
        }

        // Add OS-specific troubleshooting
        if ($this->os === 'windows') {
            $errorMessage .= "\n\nWindows troubleshooting tips:";
            $errorMessage .= "\n- Make sure k6.exe is in your PATH";
            $errorMessage .= "\n- Try running 'k6 version' in Command Prompt to verify installation";
            $errorMessage .= "\n- Check Windows Firewall settings if testing local endpoints";
            $errorMessage .= "\n- For HTTPS local testing, you might need to disable SSL verification";
        } elseif ($this->os === 'darwin') {
            $errorMessage .= "\n\nmacOS troubleshooting tips:";
            $errorMessage .= "\n- Run 'k6 version' in Terminal to verify installation";
            $errorMessage .= "\n- Check if the server is accessible: curl -v [URL]";
            $errorMessage .= "\n- Verify firewall settings in System Preferences > Security & Privacy";
        } elseif ($this->os === 'linux') {
            $errorMessage .= "\n\nLinux troubleshooting tips:";
            $errorMessage .= "\n- Run 'k6 version' to verify installation";
            $errorMessage .= "\n- Check if the server is accessible: curl -v [URL]";
            $errorMessage .= "\n- Check firewall: sudo ufw status (Ubuntu) or sudo firewall-cmd --list-all (CentOS)";
        }

        $fullOutput = !empty($errorOutput) ? $errorOutput : $output;
        if (!empty($fullOutput)) {
            $errorMessage .= "\n\nFull output:\n" . $fullOutput;
        }

        throw new Exception($errorMessage);
    }

    /**
     * Format error message with OS-specific context
     */
    protected function formatErrorMessage(string $message): string
    {
        $formatted = "k6 test execution failed.\n\n";
        $formatted .= "Error: " . $message . "\n";

        if ($this->os === 'windows') {
            $formatted .= "\nWindows-specific checks:";
            $formatted .= "\n1. Verify k6 is installed: Open Command Prompt and run 'k6 version'";
            $formatted .= "\n2. Check k6 is in PATH: Run 'where k6' in Command Prompt";
            $formatted .= "\n3. Ensure you have proper permissions to execute k6";
            $formatted .= "\n4. Try running the command as Administrator if permission issues occur";
        }

        return $formatted;
    }

    /**
     * Generate k6 script from configuration
     *
     * @param array $config Test configuration
     * @return string Path to generated script
     */
    protected function generateScript(array $config): string
    {
        $url = $config['url'];
        $method = strtoupper($config['method'] ?? 'GET');
        $headers = $config['headers'] ?? [];
        $body = $config['body'] ?? null;
        $mode = $config['mode'] ?? 'constant';

        // Build script content
        $script = "import http from 'k6/http';\n";
        $script .= "import { sleep, check } from 'k6';\n\n";

        // Add options based on mode
        $script .= "export const options = {\n";

        if ($mode === 'constant') {
            $vus = $config['vus'] ?? 10;
            $duration = $config['duration'] ?? '30s';

            $script .= "  vus: {$vus},\n";
            $script .= "  duration: '{$duration}',\n";

            if (!empty($config['iterations'])) {
                $script .= "  iterations: {$config['iterations']},\n";
            }
        } else if ($mode === 'stages') {
            $stages = $config['stages'] ?? [];
            $script .= "  stages: [\n";

            foreach ($stages as $stage) {
                $duration = $stage['duration'] ?? '30s';
                $target = $stage['target'] ?? 10;
                $script .= "    { duration: '{$duration}', target: {$target} },\n";
            }

            $script .= "  ],\n";
        }

        $script .= "};\n\n";

        // Build request params
        $script .= "export default function() {\n";
        $script .= "  const url = '{$url}';\n";

        // Prepare headers
        $allHeaders = $headers;

        // Add Content-Type for POST/PUT/PATCH if not already set
        if (in_array($method, ['POST', 'PUT', 'PATCH']) && $body) {
            $hasContentType = false;
            foreach ($allHeaders as $key => $value) {
                if (strtolower($key) === 'content-type') {
                    $hasContentType = true;
                    break;
                }
            }
            if (!$hasContentType) {
                $allHeaders['Content-Type'] = 'application/json';
            }
        }

        if (!empty($allHeaders)) {
            $script .= "  const params = {\n";
            $script .= "    headers: {\n";
            foreach ($allHeaders as $key => $value) {
                $script .= "      '{$key}': '{$value}',\n";
            }
            $script .= "    },\n";
            $script .= "  };\n";
        } else {
            $script .= "  const params = {};\n";
        }

        // Add body if POST/PUT/PATCH
        $methodLower = strtolower($method);
        if (in_array($method, ['POST', 'PUT', 'PATCH']) && $body) {
            $bodyJson = json_encode($body);
            $script .= "  const payload = " . $bodyJson . ";\n";
            $script .= "  const res = http.{$methodLower}(url, JSON.stringify(payload), params);\n";
        } else {
            $script .= "  const res = http.{$methodLower}(url, params);\n";
        }

        // Log response details for debugging
        $script .= "\n  console.log(`Request completed - Status: \${res.status}, Duration: \${res.timings.duration.toFixed(2)}ms, Size: \${res.body.length} bytes`);\n";

        // Add response checks
        $script .= "\n  check(res, {\n";
        $script .= "    'status is 200-299': (r) => r.status >= 200 && r.status < 300,\n";
        $script .= "    'response time < 500ms': (r) => r.timings.duration < 500,\n";
        $script .= "  });\n";

        // Log errors if any
        $script .= "\n  if (res.status < 200 || res.status >= 300) {\n";
        $script .= "    console.error(`HTTP Error - Status: \${res.status}, URL: \${url}`);\n";
        $script .= "    if (res.body.length < 1000) {\n";
        $script .= "      console.error(`Response body: \${res.body}`);\n";
        $script .= "    }\n";
        $script .= "  }\n";

        // Add think time (sleep)
        $thinkTime = $config['think_time'] ?? 1;
        $script .= "\n  sleep({$thinkTime});\n";
        $script .= "}\n";

        // Save script to temp file
        $tempDir = sys_get_temp_dir();
        $scriptPath = $tempDir . DIRECTORY_SEPARATOR . 'k6_test_' . uniqid() . '.js';

        // Ensure directory exists and is writable
        if (!is_dir($tempDir)) {
            throw new Exception("Temp directory does not exist: {$tempDir}");
        }

        if (!is_writable($tempDir)) {
            $errorMsg = "Temp directory is not writable: {$tempDir}";
            if ($this->os === 'windows') {
                $errorMsg .= "\n\nWindows troubleshooting:";
                $errorMsg .= "\n- Try running the application as Administrator";
                $errorMsg .= "\n- Check folder permissions in File Explorer";
                $errorMsg .= "\n- Ensure antivirus is not blocking write access";
            }
            throw new Exception($errorMsg);
        }

        $result = file_put_contents($scriptPath, $script);

        if ($result === false) {
            $errorMsg = "Failed to write k6 script to: {$scriptPath}";
            if ($this->os === 'windows') {
                $errorMsg .= "\n\nWindows troubleshooting:";
                $errorMsg .= "\n- Check if antivirus is blocking file creation";
                $errorMsg .= "\n- Verify disk space is available";
                $errorMsg .= "\n- Try running as Administrator";
            }
            throw new Exception($errorMsg);
        }

        return $scriptPath;
    }

    /**
     * Build k6 command based on OS
     *
     * @param string $scriptPath Path to k6 script
     * @return string Command to execute
     */
    protected function buildCommand(string $scriptPath): string
    {
        // Use full path to k6 command
        $k6Path = $this->k6Command;

        // For Windows, ensure proper quoting if path contains spaces
        if ($this->os === 'windows') {
            if (strpos($k6Path, ' ') !== false && strpos($k6Path, '"') !== 0) {
                $k6Path = '"' . $k6Path . '"';
            }
            $scriptPath = str_replace('/', '\\', $scriptPath);
            $outputPath = str_replace('/', '\\', sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'k6_results_' . uniqid() . '.json');
        } else {
            $outputPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'k6_results_' . uniqid() . '.json';
        }

        // Build command with full k6 path
        $command = $k6Path . ' run';

        // Add JSON output for easier parsing
        $command .= ' --out json="' . $outputPath . '"';

        // Add script path with proper quoting
        $command .= ' "' . $scriptPath . '"';

        return $command;
    }

    /**
     * Parse k6 output to extract metrics
     *
     * @param string $output Raw k6 output
     * @return array Parsed metrics
     */
    protected function parseOutput(string $output): array
    {
        $metrics = [
            'success' => true,
            'output' => $output,
            'summary' => [],
        ];

        // Parse summary metrics from output
        if (preg_match('/http_reqs.*?(\d+)/', $output, $matches)) {
            $metrics['summary']['total_requests'] = (int) $matches[1];
        }

        if (preg_match('/http_req_duration.*?avg=([\d.]+)ms/', $output, $matches)) {
            $metrics['summary']['avg_response_time'] = $matches[1] . 'ms';
        }

        if (preg_match('/http_req_failed.*?([\d.]+)%/', $output, $matches)) {
            $metrics['summary']['failure_rate'] = $matches[1] . '%';
        }

        if (preg_match('/iterations.*?(\d+)/', $output, $matches)) {
            $metrics['summary']['iterations'] = (int) $matches[1];
        }

        if (preg_match('/vus_max.*?(\d+)/', $output, $matches)) {
            $metrics['summary']['max_vus'] = (int) $matches[1];
        }

        // Parse data sent/received to detect if HTTP requests actually happened
        if (preg_match('/data_received[.:\s]+(\d+(?:\.\d+)?)\s*([kMG]?B)/', $output, $matches)) {
            $value = (float) $matches[1];
            $unit = $matches[2];
            $metrics['summary']['data_received'] = $value . ' ' . $unit;
            $metrics['summary']['data_received_bytes'] = $this->convertToBytes($value, $unit);
        }

        if (preg_match('/data_sent[.:\s]+(\d+(?:\.\d+)?)\s*([kMG]?B)/', $output, $matches)) {
            $value = (float) $matches[1];
            $unit = $matches[2];
            $metrics['summary']['data_sent'] = $value . ' ' . $unit;
            $metrics['summary']['data_sent_bytes'] = $this->convertToBytes($value, $unit);
        }

        // Check if HTTP traffic actually occurred
        $hasHttpTraffic = false;
        if (isset($metrics['summary']['total_requests']) && $metrics['summary']['total_requests'] > 0) {
            $hasHttpTraffic = true;
        }
        if (isset($metrics['summary']['data_sent_bytes']) && $metrics['summary']['data_sent_bytes'] > 0) {
            $hasHttpTraffic = true;
        }
        if (isset($metrics['summary']['data_received_bytes']) && $metrics['summary']['data_received_bytes'] > 0) {
            $hasHttpTraffic = true;
        }

        $metrics['summary']['has_http_traffic'] = $hasHttpTraffic;

        return $metrics;
    }

    /**
     * Convert data size to bytes
     */
    protected function convertToBytes(float $value, string $unit): int
    {
        $units = [
            'B' => 1,
            'kB' => 1024,
            'MB' => 1024 * 1024,
            'GB' => 1024 * 1024 * 1024,
        ];

        return (int) ($value * ($units[$unit] ?? 1));
    }

    /**
     * Generate k6 script content without running
     *
     * @param array $config Test configuration
     * @return string Script content
     */
    public function generateScriptContent(array $config): string
    {
        try {
            $scriptPath = $this->generateScript($config);
            $content = file_get_contents($scriptPath);

            // Clean up
            if (file_exists($scriptPath)) {
                unlink($scriptPath);
            }

            return $content;
        } catch (Exception $e) {
            $errorMessage = "Failed to generate k6 script.\n\n";
            $errorMessage .= "Error: " . $e->getMessage();

            if ($this->os === 'windows') {
                $errorMessage .= "\n\nWindows troubleshooting:";
                $errorMessage .= "\n- Check if you have write permissions to temp directory: " . sys_get_temp_dir();
                $errorMessage .= "\n- Ensure no antivirus is blocking file creation";
                $errorMessage .= "\n- Try running the application as Administrator";
            }

            throw new Exception($errorMessage);
        }
    }

    /**
     * Get OS information
     *
     * @return array OS details
     */
    public function getSystemInfo(): array
    {
        return [
            'os' => $this->os,
            'php_os' => PHP_OS,
            'k6_path' => $this->k6Command ?? 'Not installed',
        ];
    }
}
