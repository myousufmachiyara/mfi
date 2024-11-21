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

            // Use platform-specific commands to fetch MAC address
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows system: Using getmac
                $process = new Process(['getmac']);
            } else {
                // Unix-based system (Linux, macOS): Using ifconfig
                $process = new Process(['ifconfig', '-a']);
            }

            // Execute the command
            $process->run();

            // Check if the command was successful
            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                \Log::info('Command Output: ' . $output); // Log the output for debugging

                // Extract MAC address from command output
                preg_match('/([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}/', $output, $matches);

                // Log the matches found
                \Log::info('MAC Address Matches: ' . json_encode($matches));

                $macAddress = $matches[0] ?? 'MAC address not found'; // Fallback message if no MAC found
            } else {
                // If the command fails, log the error output
                \Log::error('Command failed: ' . $process->getErrorOutput());
                $macAddress = 'MAC address not found';
            }

            return $macAddress ?: 'MAC address not found'; // Fallback message in case no MAC found
        } catch (\Exception $e) {
            // Log the exception message for debugging
            \Log::error('Error fetching MAC address: ' . $e->getMessage());
            return 'MAC address not found'; // Fallback message in case of error
        }
    }
}
