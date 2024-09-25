<?php

namespace App\Http\Controllers;

use App\Http\Requests\QrCodeRequest;
use App\Models\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode as GenerateQrCode;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('qrcode.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QrCodeRequest $request)
    {
        $data = $request->validated();

        try {
            $quantity = $data['quantity'];
            $internalQRCodes = [];
            $externalQRCodes = [];

            // Get the current user's information
            $user = Auth::user();
            $userId = $user->id;
            $userName = $user->name;
            $userEmail = $user->email;

            // Loop through the quantity and generate internal/external QR codes
            for ($i = 0; $i < $quantity; $i++) {
                // Generate a unique number for each QR code
                $uniqueNumber = uniqid();

                // Define QR code size
                $qrCodeSize = 300;

                // Generate Internal QR Code (embed user information in the QR content)
                $internalQRCodeContent = GenerateQrCode::size($qrCodeSize)
                    ->generate("Internal QR\nUser: $userName\nEmail: $userEmail\nUniqueID: $uniqueNumber");

                // Generate External QR Code (embed user information in the QR content)
                $externalQRCodeContent = GenerateQrCode::size($qrCodeSize)
                    ->generate("External QR\nUser: $userName\nEmail: $userEmail\nUniqueID: $uniqueNumber");

                // Append to internal and external QR code arrays
                $internalQRCodes[] = [
                    'qr_code' => $internalQRCodeContent,
                    'unique_number' => $uniqueNumber . '_internal',
                    'status' => 'active'
                ];

                $externalQRCodes[] = [
                    'qr_code' => $externalQRCodeContent,
                    'unique_number' => $uniqueNumber . '_external',
                    'status' => 'active'
                ];
            }

            // Create a new QrCode record in the database
            $qrCode = new QrCode();
            $qrCode->unique_number = uniqid();
            $qrCode->user_id = $userId;
            $qrCode->quantity = $quantity;
            $qrCode->internal_qr_code_count = count($internalQRCodes);
            $qrCode->external_qr_code_count = count($externalQRCodes);
            $qrCode->internal_qr_code_status = 'active';
            $qrCode->external_qr_code_status = 'active';
            $qrCode->inserted_dt = Carbon::now();
            $qrCode->inserted_by = $userId;
            $qrCode->save();

            // Generate PDF with the QR codes
            $pdf = Pdf::loadView('qrcode.pdf', [
                'internalQRCodes' => $internalQRCodes,
                'externalQRCodes' => $externalQRCodes,
                'uniqueNumber' => $qrCode->unique_number,
                'user' => $user,
            ]);

            // Return the PDF as a stream (opens in a new tab) and download
            return $pdf->stream($qrCode->unique_number . '_QR_codes.pdf');

        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something went wrong - ' . $ex->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QrCodeRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
