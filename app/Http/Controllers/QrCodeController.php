<?php

namespace App\Http\Controllers;

use App\Http\Requests\QrCodeRequest;
use App\Models\QrCode;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qrcodes = QrCode::with('user')->orderBy("id", "desc")->whereNull('deleted_at')->get();

        return view('qrcode.index', [
            'qrcodes' => $qrcodes
        ]);
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
            $internalQRCodes = [];  // Array to store internal QR codes
            $externalQRCodes = [];  // Array to store external QR codes

            // Define path where QR codes will be stored
            $qrCodePath = public_path('wsrl/images/qrcodes');

            // Create the directory if it doesn't exist
            if (!file_exists($qrCodePath)) {
                mkdir($qrCodePath, 0775, true);
            }

            // Generate a unique number for the QR codes
            $uniqueNumber = uniqid();

            // Generate Internal QR
            $internalQRCodeContent = QRCodeService::generate([
                'content' => 'Internal: ' . $uniqueNumber,
                'unique_number' => $uniqueNumber . '_internal'
            ]);

            // Generate External QR
            $externalQRCodeContent = QRCodeService::generate([
                'content' => 'External: ' . $uniqueNumber,
                'unique_number' => $uniqueNumber . '_external'
            ]);

            // Check if QR codes are generated
            if (!$internalQRCodeContent || !$externalQRCodeContent) {
                return redirect()->back()->with('error', 'Failed to generate QR codes.');
            }

            // Ensure content is in the correct format
            if (!is_string($internalQRCodeContent) || !is_string($externalQRCodeContent)) {
                return redirect()->back()->with('error', 'QR code content generation returned an invalid format.');
            }

            // Save Internal QR code as an image file
            $internalFileName = $uniqueNumber . '_internal.png';
            if (file_put_contents($qrCodePath . '/' . $internalFileName, $internalQRCodeContent) === false) {
                return redirect()->back()->with('error', 'Failed to save internal QR code.');
            }

            // Save External QR code as an image file
            $externalFileName = $uniqueNumber . '_external.png';
            if (file_put_contents($qrCodePath . '/' . $externalFileName, $externalQRCodeContent) === false) {
                return redirect()->back()->with('error', 'Failed to save external QR code.');
            }

            // Append to internal and external QR code arrays with only filenames
            $internalQRCodes[] = [
                'qr_code' => $internalFileName,  // Store only the filename
                'unique_number' => $uniqueNumber . '_internal',
                'status' => 'active'
            ];

            $externalQRCodes[] = [
                'qr_code' => $externalFileName,  // Store only the filename
                'unique_number' => $uniqueNumber . '_external',
                'status' => 'active'
            ];

            // Create a new QrCode record (with JSON fields)
            $qrCode = new QrCode();
            $qrCode->unique_number = $uniqueNumber;
            $qrCode->user_id = Auth::user()->id;
            $qrCode->quantity = $quantity; // Store the total quantity
            $qrCode->internal_qr_code = json_encode(array_column($internalQRCodes, 'qr_code'));  // Store only filenames as JSON
            $qrCode->external_qr_code = json_encode(array_column($externalQRCodes, 'qr_code'));  // Store only filenames as JSON

            // Save the internal_qr_code_count
            $qrCode->internal_qr_code_count = count($internalQRCodes);

            // Save the external_qr_code_count
            $qrCode->external_qr_code_count = count($externalQRCodes);

            // Save the internal_qr_code_status (enum('active', 'printed', 'scanned'))
            $qrCode->internal_qr_code_status = 'active';

            // Save the external_qr_code_status (enum('active', 'printed', 'scanned'))
            $qrCode->external_qr_code_status = 'active';

            // Additional fields
            $qrCode->inserted_dt = Carbon::now();
            $qrCode->inserted_by = Auth::user()->id;

            // Save the record
            $qrCode->save();

            return redirect()->route('qrcode.index')->with('message', 'QR codes have been successfully generated.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something went wrong - ' . $ex->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $qrCode = QrCode::findOrFail($id);

        // Decode the internal and external QR code filenames from JSON
        $internalQRCodes = json_decode($qrCode->internal_qr_code);
        $externalQRCodes = json_decode($qrCode->external_qr_code);

        if (!$internalQRCodes || !$externalQRCodes) {
            return redirect()->back()->with('error', 'Failed to retrieve QR codes.');
        }

        // Define path where QR codes are stored
        $qrCodePath = public_path('wsrl/images/qrcodes');

        return view('qrcode.show', [
            'qrCode' => $qrCode,
            'internalQRCodes' => $internalQRCodes,
            'externalQRCodes' => $externalQRCodes,
            'qrCodePath' => $qrCodePath
        ]);
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
