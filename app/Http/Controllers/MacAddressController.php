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
        try {
            // Attempt to fetch MAC address using system commands
            $macAddress = $this->fetchMacAddressFromSystem();

            // If MAC address not found or access is restricted, return fallback information (IP or hostname)
            if ($macAddress === 'MAC address not found') {
                // Try fetching server IP and hostname as an alternative
                $macAddress = $this->getServerIpOrHostname();
            }

            return $macAddress;
        } catch (\Exception $e) {
            // If an error occurs, log and return fallback
            \Log::error('Error fetching MAC address: ' . $e->getMessage());
            return 'MAC address not found';
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

    private function getServerIpOrHostname()
    {
        // Attempt to get IP or hostname as fallback
        $serverIp = $_SERVER['SERVER_ADDR'] ?? 'IP not found';
        $serverHostname = gethostname() ?? 'Hostname not found';

        return "IP: $serverIp, Hostname: $serverHostname";
    }
}
