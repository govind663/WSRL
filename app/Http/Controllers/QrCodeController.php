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
    $data = $request->validated();  // Validate the request data

    try {
        $quantity = $data['quantity'];

        for ($i = 0; $i < $quantity; $i++) {
            // Generate a unique number for each QR code
            $uniqueNumber = uniqid();

            // Generate Internal QR
            $internalQRCode = QRCodeService::generate([
                'content' => 'Internal: ' . $uniqueNumber,
                'unique_number' => $uniqueNumber . '_internal'
            ]);

            // Generate External QR
            $externalQRCode = QRCodeService::generate([
                'content' => 'External: ' . $uniqueNumber,
                'unique_number' => $uniqueNumber . '_external'
            ]);

            // Create a new QrCode record
            $qrCode = new QrCode();
            $qrCode->user_id = Auth::user()->id;
            $qrCode->quantity = 1;
            $qrCode->internal_qr_code = $internalQRCode;
            $qrCode->external_qr_code = $externalQRCode;
            $qrCode->unique_number = $uniqueNumber;

            // add internal_qr_code_count based on paring both internally or externelly
            $qrCode->internal_qr_code_count = QrCode::where('internal_qr_code', $internalQRCode)->count();
            $qrCode->external_qr_code_count = QrCode::where('external_qr_code', $externalQRCode)->count();

            // add internal_qr_code_status enum('active', 'printed', 'scanned')
            $qrCode->internal_qr_code_status = 'active';

            // add external_qr_code_status enum('active', 'printed', 'scanned')
            $qrCode->external_qr_code_status = 'active';

            // Additional fields
            $qrCode->inserted_dt = Carbon::now();
            $qrCode->inserted_by = Auth::user()->id;

            $qrCode->save();
        }

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
