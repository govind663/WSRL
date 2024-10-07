<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrCode;

class QrCodedetatilsController extends Controller
{
    public function show(string $unique_number)
    {
        // Retrieve the QR code record from the database where the unique number is in the internal_qr_code JSON field
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
        dd($isInternal);
        $isExternal = in_array($unique_number, $externalQrCodes);

        // Return a view with the details of the QR code
        return view('qrcode.show', [
            'qrCode' => $qrCode,
            'isInternal' => $isInternal,
            'isExternal' => $isExternal,
            'internalQrCodes' => $internalQrCodes,
            'externalQrCodes' => $externalQrCodes,
            'scannedNumber' => $unique_number
        ]);
    }


}
