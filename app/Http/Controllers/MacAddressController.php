<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class MacAddressController extends Controller
{
    public function showMacAddress()
    {
        $macAddress = $this->getMacAddress();
        $motherboardId = $this->getMotherboardId();
        $hardDriveId = $this->getHardDriveId();

        return view('mac_address', compact('macAddress', 'motherboardId', 'hardDriveId'));
    }

    private function getMacAddress()
    {
        try {
            // Attempt to get MAC address (depending on platform)
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // For Windows
                $process = new Process(['getmac']);
            } else {
                // For Linux or macOS
                $process = new Process(['ifconfig', '-a']);
            }

            $process->run();

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                preg_match('/([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}/', $output, $matches);
                return $matches[0] ?? 'MAC address not found';
            }

            return 'MAC address not found';
        } catch (\Exception $e) {
            \Log::error('Error fetching MAC address: ' . $e->getMessage());
            return 'MAC address not found';
        }
    }

    private function getMotherboardId()
    {
        try {
            // Windows command to get motherboard ID
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $process = new Process(['wmic', 'baseboard', 'get', 'serialnumber']);
            } else {
                // Linux/macOS command to get motherboard ID
                $process = new Process(['dmidecode', '-t', 'baseboard']);
            }

            $process->run();

            // Log the raw output to debug
            \Log::info('Motherboard Output: ' . $process->getOutput());

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                // Clean the output and check for the presence of serial number
                $output = trim($output);
                \Log::info('Trimmed Output: ' . $output);  // Log the cleaned output

                // Try a broader search for serial number or motherboard identifier
                preg_match('/(?:SerialNumber\s*[:\-]?\s*)([A-Za-z0-9\-]+)/', $output, $matches);
                return $matches[1] ?? 'Motherboard ID not found';
            }

            return 'Motherboard ID not found';
        } catch (\Exception $e) {
            \Log::error('Error fetching motherboard ID: ' . $e->getMessage());
            return 'Motherboard ID not found';
        }
    }

    private function getHardDriveId()
    {
        try {
            // Windows command to get hard drive ID
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $process = new Process(['wmic', 'diskdrive', 'get', 'serialnumber']);
            } else {
                // Linux/macOS command to get hard drive ID
                $process = new Process(['lsblk', '-o', 'NAME,SERIAL']);
            }

            $process->run();

            // Log the raw output to debug
            \Log::info('Hard Drive Output: ' . $process->getOutput());

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                // Clean the output and check for the presence of serial number
                $output = trim($output);
                \Log::info('Trimmed Output: ' . $output);  // Log the cleaned output

                // Try a broader search for serial number or disk identifier
                preg_match('/(?:SerialNumber\s*[:\-]?\s*)([A-Za-z0-9\-]+)/', $output, $matches);
                return $matches[1] ?? 'Hard drive ID not found';
            }

            return 'Hard drive ID not found';
        } catch (\Exception $e) {
            \Log::error('Error fetching hard drive ID: ' . $e->getMessage());
            return 'Hard drive ID not found';
        }
    }
}
