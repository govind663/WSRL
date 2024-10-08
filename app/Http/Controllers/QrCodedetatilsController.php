<?php

namespace App\Http\Controllers;

use App\Models\QrCodeScan;
use Illuminate\Http\Request;
use App\Models\QrCode;
use Carbon\Carbon;

class QrCodedetatilsController extends Controller
{
    public function show(string $unique_number)
    {
        // Retrieve the QR code record from the database
        $qrCode = QrCode::with('product', 'user')
            ->whereRaw("JSON_CONTAINS(internal_qr_code, '\"$unique_number\"')")
            ->orWhereRaw("JSON_CONTAINS(external_qr_code, '\"$unique_number\"')")
            ->first();

        // Check if QR code exists
        // if (!$qrCode) {
        //     return redirect()->back()->with('error', 'QR code not found.');
        // }

        // Convert the JSON fields back into arrays for easier access
        $internalQrCodes = json_decode($qrCode->internal_qr_code, true);
        $externalQrCodes = json_decode($qrCode->external_qr_code, true);

        // Determine if the scanned number is internal or external
        $isInternal = in_array($unique_number, $internalQrCodes);
        $isExternal = in_array($unique_number, $externalQrCodes);

        // Increment scan count and store the scan record
        if ($isInternal) {
            $qrCode->increment('internal_qr_code_scan_count'); // Increment without limit

            // Store scan record
            QrCodeScan::create([
                'qr_code_id' => $qrCode->id,
                'type' => 'internal',
                'inserted_dt' => Carbon::now()->format('Y-m-d H:i:s'),
                'inserted_by' => $qrCode->id,
            ]);
        } elseif ($isExternal) {
            $qrCode->increment('external_qr_code_scan_count'); // Increment without limit

            // Store scan record
            QrCodeScan::create([
                'qr_code_id' => $qrCode->id,
                'type' => 'external',
                'inserted_dt' => Carbon::now()->format('Y-m-d H:i:s'),
                'inserted_by' => $qrCode->id,
            ]);
        } else {
            return redirect()->back()->with('error', 'QR code type not recognized.');
        }

        // Prepare data for the view
        $viewData = [
            'qrCode' => $qrCode,
            'internalQrCodes' => $internalQrCodes,
            'externalQrCodes' => $externalQrCodes,
            'scannedNumber' => $unique_number,
            'isInternal' => $isInternal,
            'isExternal' => $isExternal,
            'internalQrCodeCount' => $qrCode->internal_qr_code_scan_count, // Pass internal count
            'externalQrCodeCount' => $qrCode->external_qr_code_scan_count, // Pass external count
            'scanRecords' => QrCodeScan::where('qr_code_id', $qrCode->id)->count(),
        ];

        // Return appropriate view based on the QR code type
        if ($isInternal) {
            return view('qrcode.show', $viewData);
        } elseif ($isExternal) {
            return view('qrcode.mobile_verification', $viewData);
        }

        return redirect()->back()->with('error', 'QR code type not recognized.');
    }

}
