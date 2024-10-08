<?php

namespace App\Http\Controllers;

use App\Models\Otp;
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

    public function generateOtp(Request $request)
    {
        // Validate mobile number input
        $request->validate([
            'mobile_number' => 'required|numeric|digits:10',
        ]);

        // Generate a random OTP
        $otp = rand(100000, 999999);

        // Store OTP in session for later verification
        session(['otp' => $otp]);

        // Store OTP in the database with an expiration time (optional)
        Otp::create([
            'mobile_number' => $request->mobile_number,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10), // OTP expires in 10 minutes
            'inserted_dt' => Carbon::now(),
            'inserted_by' => 1,
        ]);
        // Request scannedNumber
        $scannedNumber = $request->input('scannedNumber');

        // Return a view displaying the OTP
        return view('otp.show', ['scannedNumber' => $scannedNumber, 'otp' => $otp, 'mobile_number' => $request->mobile_number]);
    }

    public function verifyOtp(Request $request)
    {
        // Validate the OTP input
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        // Check if the OTP matches the one in session
        if ($request->otp == session('otp')) {
            // OTP is correct, proceed with verification success logic
            session()->forget('otp'); // Clear the OTP after successful verification

            // === fetch scannedNumber
            $unique_number = $request->input('scannedNumber');

            // Retrieve the QR code record from the database
            $qrCode = QrCode::with('product', 'user')
                            ->whereRaw("JSON_CONTAINS(internal_qr_code, '\"$unique_number\"')")
                            ->orWhereRaw("JSON_CONTAINS(external_qr_code, '\"$unique_number\"')")
                            ->first();

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
            return view('qrcode.show',['viewData' => $viewData])->with('success', 'OTP verified successfully!');
        } else {
            // OTP is incorrect
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    }

}
