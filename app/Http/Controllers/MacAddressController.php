<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class MacAddressController extends Controller
{
    public function showMacAddress()
    {
        $macAddress = $this->getMacAddress();
        return view('mac_address', compact('macAddress'));
    }

    private function getMacAddress()
    {
        // Check if we're in a shared hosting environment (e.g., cPanel)
        if ($this->isSharedHosting()) {
            // Simulate MAC address on shared hosting
            return $this->simulateMacAddress();
        }

        try {
            $macAddress = null;

            // Use platform-specific commands to fetch MAC address
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows system
                $process = new Process(['getmac']);
            } else {
                // Unix-based system (Linux, macOS)
                $process = new Process(['ifconfig', '-a']);
            }

            // Execute the command
            $process->run();

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                \Log::info('Command Output: ' . $output); // Log for debugging

                // Extract MAC address from command output
                preg_match('/([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}/', $output, $matches);
                $macAddress = $matches[0] ?? null;
            }

            return $macAddress ?: $this->getServerInfo();
        } catch (\Exception $e) {
            \Log::error('Error fetching MAC address: ' . $e->getMessage());
            return $this->getServerInfo();
        }
    }

    private function isSharedHosting()
    {
        // This check is a simple example. You may need to adjust this based on your hosting provider.
        // For example, you can look for specific environment variables that indicate shared hosting (e.g., cPanel, shared hosting names).
        return strpos($_SERVER['SERVER_NAME'], 'wehostwebserver') !== false; // Example condition for shared hosting
    }

    private function simulateMacAddress()
    {
        // Return a fake MAC address for shared hosting
        return 'C8-D3-FF-BB-1B-AF'; // Fake MAC address for simulation
    }

    private function getServerInfo()
    {
        try {
            $serverInfo = [];

            // Fetch server hostname and IP address as fallback
            $hostname = gethostname();
            $ipAddress = $_SERVER['SERVER_ADDR'];

            $serverInfo['hostname'] = $hostname ?: 'Hostname not available';
            $serverInfo['ipAddress'] = $ipAddress ?: 'IP Address not available';

            return $serverInfo;
        } catch (\Exception $e) {
            \Log::error('Error fetching server information: ' . $e->getMessage());
            return 'Error fetching server information';
        }
    }
}
