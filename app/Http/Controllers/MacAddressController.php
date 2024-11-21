<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class DeviceController extends Controller
{
    public function showUniqueDeviceID()
    {
        $deviceId = $this->getUniqueDeviceID();
        return view('device_id', compact('deviceId'));
    }

    private function getUniqueDeviceID()
    {
        try {
            // Attempt to get hard drive serial number (Windows/Linux)
            $deviceId = $this->fetchHardDriveSerialNumber();

            // If hard drive serial number isn't available, fall back to motherboard serial number
            if ($deviceId === 'Serial number not found') {
                $deviceId = $this->fetchMotherboardSerialNumber();
            }

            return $deviceId;

        } catch (\Exception $e) {
            \Log::error('Error fetching device ID: ' . $e->getMessage());
            return 'Device ID not found';
        }
    }

    private function fetchHardDriveSerialNumber()
    {
        // Check if we are on Windows or Linux and run the appropriate command
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $process = new Process(['wmic', 'diskdrive', 'get', 'serialnumber']);
        } else {
            $process = new Process(['lsblk', '-o', 'SERIAL', '--nodeps']);
        }

        $process->run();

        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            preg_match('/[A-Za-z0-9]+/', $output, $matches);
            return $matches[0] ?? 'Serial number not found';
        }

        return 'Serial number not found';
    }

    private function fetchMotherboardSerialNumber()
    {
        // Fetch motherboard serial number (only Windows in this case)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $process = new Process(['wmic', 'baseboard', 'get', 'serialnumber']);
            $process->run();

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                preg_match('/[A-Za-z0-9]+/', $output, $matches);
                return $matches[0] ?? 'Motherboard serial number not found';
            }
        }

        return 'Motherboard serial number not found';
    }
}
