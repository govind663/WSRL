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
        ->first();

    // Check if QR code exists
    // if (!$qrCode) {
    //     return redirect()->back()->with('error', 'QR code not found.');
    // }

    // Return a view with the details of the QR code
    return view('qrcode.show', [
        'qrCode' => $qrCode,
    ]);
}

}
