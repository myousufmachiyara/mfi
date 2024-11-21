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

    // private function getMacAddress()
    // {
    //     try {
    //         $macAddress = null;

    //         if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    //             // Windows system
    //             $output = shell_exec('getmac');
    //             if ($output) {
    //                 $lines = explode("\n", trim($output));
    //                 foreach ($lines as $line) {
    //                     if (strpos($line, 'Physical Address') === false && preg_match('/([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}/', $line, $matches)) {
    //                         $macAddress = $matches[0];
    //                         break;
    //                     }
    //                 }
    //             }
    //         } else {
    //             // Unix-based system (Linux, macOS)
    //             $output = shell_exec('ifconfig -a');
    //             if ($output) {
    //                 preg_match('/([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}/', $output, $matches);
    //                 $macAddress = $matches[0] ?? null;
    //             }
    //         }

    //         return $macAddress ?: 'MAC address not found';
    //     } catch (\Exception $e) {
    //         \Log::error('Error fetching MAC address: ' . $e->getMessage());
    //         return 'Error fetching MAC address';
    //     }
    // }

    use Symfony\Component\Process\Process;

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

            return $macAddress ?: 'MAC address not found';
        } catch (\Exception $e) {
            \Log::error('Error fetching MAC address: ' . $e->getMessage());
            return 'Error fetching MAC address';
        }
    }

}
