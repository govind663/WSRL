<?php

namespace App\Http\Controllers;

use App\Http\Requests\QrCodeRequest;
use App\Models\Product;
use App\Models\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode as GenerateQrCode;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

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
        // == Fetch Product Name & Id
        $products = Product::whereNull('deleted_at')->orderBy('id', 'asc')->get(['id', 'name']);

        return view('qrcode.create', [
            'products' => $products
        ]);
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

            // Assuming the product details are fetched based on product_id
            $product = Product::find($request->input('product_id'));
            $producSKU = $product->sku;
            $productName = $product->name;
            $productDescription = strip_tags($product->description);

            // Loop through the quantity and generate internal/external QR codes
            for ($i = 0; $i < $quantity; $i++) {
                // Generate a unique number for each QR code
                $uniqueNumber = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 6);

                // Define QR code size
                $qrCodeSize = 300;

                // Generate Internal QR Code URL
                $internalQRCodeUrl = route('qr.show', ['unique_number' => $uniqueNumber . '_internal']);
                $internalQRCodeContent = GenerateQrCode::size($qrCodeSize)->generate($internalQRCodeUrl);

                // Generate External QR Code URL
                $externalQRCodeUrl = route('qr.show', ['unique_number' => $uniqueNumber . '_external']);
                $externalQRCodeContent = GenerateQrCode::size($qrCodeSize)->generate($externalQRCodeUrl);

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
            $qrCode->product_id = $request->input('product_id');
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

            return $pdf->stream($qrCode->unique_number . '_QR_codes.pdf');

        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something went wrong - ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, $unique_number)
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
