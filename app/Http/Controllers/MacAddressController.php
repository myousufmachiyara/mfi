<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;

class MacAddressController extends Controller
{
    public function showMacAddress()
    {
        $deviceAddress = $this->getDeviceAddress();
        return view('mac_address', compact('deviceAddress'));
    }

    private function getDeviceAddress()
    {
        try {
            // Attempt to fetch MAC address using system commands
            $macAddress = $this->fetchMacAddressFromSystem();

            // If MAC address not found or access is restricted, return fallback information (IP, Hostname, or UUID)
            if ($macAddress === 'MAC address not found') {
                $macAddress = $this->getFallbackDeviceAddress();
            }

            return $macAddress;
        } catch (\Exception $e) {
            // If an error occurs, log and return fallback
            \Log::error('Error fetching device address: ' . $e->getMessage());
            return 'Device address not found';
        }
    }

    private function fetchMacAddressFromSystem()
    {
        // Use platform-specific commands to fetch MAC address
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows system: Using getmac
            $process = new Process(['getmac']);
        } else {
            // Unix-based system (Linux, macOS): Using ifconfig
            $process = new Process(['ifconfig', '-a']);
        }

        $process->run();

        // Check if command was successful
        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            preg_match('/([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}/', $output, $matches);
            return $matches[0] ?? 'MAC address not found';
        }

        // Command failed, returning fallback message
        return 'MAC address not found';
    }

    private function getFallbackDeviceAddress()
    {
        // Get server's IP address (unique to the server)
        $serverIp = $_SERVER['SERVER_ADDR'] ?? 'IP not found';

        // Get the server's hostname
        $serverHostname = gethostname() ?? 'Hostname not found';

        // Generate a UUID (unique identifier)
        $uuid = (string) Str::uuid();

        // Return a combination of IP, Hostname, or UUID based on what you need
        return "IP: $serverIp, Hostname: $serverHostname, UUID: $uuid";
    }
}
