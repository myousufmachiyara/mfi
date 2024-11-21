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
            $macAddress = null;

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows system
                $process = new Process(['getmac']);
            } else {
                // Unix-based system (Linux, macOS)
                $process = new Process(['ifconfig', '-a']);
            }

            $process->run();

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                \Log::info('Command Output: ' . $output); // Log the command output for debugging

                // Attempt to extract a MAC address
                preg_match('/([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}/', $output, $matches);
                $macAddress = $matches[0] ?? null;
            }

            return $macAddress ?: $this->getServerInfo();
        } catch (\Exception $e) {
            \Log::error('Error fetching MAC address: ' . $e->getMessage());
            return $this->getServerInfo();
        }
    }

    private function getServerInfo()
    {
        try {
            $serverInfo = [];

            // Get server's hostname and IP address as fallback
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
