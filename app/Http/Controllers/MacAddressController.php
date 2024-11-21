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
                $macAddress = $matches[0] ?? 'MAC address not found'; // Return fallback message if no MAC found
            }

            return $macAddress;
        } catch (\Exception $e) {
            \Log::error('Error fetching MAC address: ' . $e->getMessage());
            return 'MAC address not found'; // Fallback message in case of error
        }
    }

    private function isSharedHosting()
    {
        // Simple check for shared hosting
        return strpos($_SERVER['SERVER_NAME'], 'wehostwebserver') !== false; // Example check
    }

    private function simulateMacAddress()
    {
        // Return a fake MAC address for shared hosting
        return 'C8-D3-FF-BB-1B-AF'; // Simulated MAC address
    }
}
