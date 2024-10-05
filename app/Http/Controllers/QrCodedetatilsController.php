<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrCode;

class QrCodedetatilsController extends Controller
{
    public function show(string $unique_number)
    {
        // Retrieve the QR code record from the database
        $qrCode = QrCode::where('unique_number', $unique_number)->first();

        if (!$qrCode) {
            return redirect()->back()->with('error', 'QR code not found.');
        }

        // Return a view with the details of the QR code
        return view('qrcode.show', [
            'qrCode' => $qrCode,
        ]);
    }
}
