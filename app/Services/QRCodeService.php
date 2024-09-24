<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeService
{
    /**
     * Generate a QR code and save it to the file system.
     *
     * @param array $data
     * @return string Path to the saved QR code image
     */
    public static function generate($data)
    {
        // Set the path to save the QR code
        $qrCodePath = public_path('wsrl/images/qrcodes/') . $data['unique_number'] . '.png';

        // Generate the QR code and save it in PNG format
        try {
            QrCode::format('png')
                    ->size(200)
                    ->generate($data['content'], $qrCodePath);

            // Check if file was created
            if (!file_exists($qrCodePath)) {
                throw new \Exception('Failed to generate QR code image.');
            }

            return $qrCodePath;
        } catch (\Exception $e) {
            // Log the error
            Log::error('QR Code generation error: ' . $e->getMessage());
            return null; // or handle error appropriately
        }
    }

}
