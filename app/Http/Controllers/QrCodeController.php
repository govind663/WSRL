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
            $productDescription = $product->description;
            $productDescription = strip_tags($productDescription);

            // Loop through the quantity and generate internal/external QR codes
            for ($i = 0; $i < $quantity; $i++) {
                // Generate a unique number for each QR code
                $uniqueNumber = uniqid();

                // Define QR code size
                $qrCodeSize = 300;

                // Generate Internal QR Code (embed user information in the QR content)
                $internalQRCodeContent = GenerateQrCode::size($qrCodeSize)
                    ->generate("
                        Internal QR\n
                        User Detail : - \n
                        Name : $userName\n
                        Email : $userEmail\n
                        UniqueID : $uniqueNumber\n
                        Product Detail : - \n
                        SKU : $producSKU\n
                        Name : $productName\n
                        Description : $productDescription\n
                    ");

                // Generate External QR Code (embed user information in the QR content)
                $externalQRCodeContent = GenerateQrCode::size($qrCodeSize)
                    ->generate("
                        External QR\n
                        User Detail : - \n
                        Name : $userName\n
                        Email : $userEmail\n
                        UniqueID : $uniqueNumber\n
                        Product Detail : - \n
                        SKU : $producSKU\n
                        Name : $productName\n
                        Description : $productDescription\n
                    ");

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

            // ==== Update avilable quantity in product table based in product id
            $actualAvailableQuantity = $request->input('avilable_product_quantity');
            $currentQuantity = $request->input('current_product_quantity');
            $newQuantity = $actualAvailableQuantity - $currentQuantity;
            $update = [
                'available_quantity' => $newQuantity,
                'modified_at' => Carbon::now(),
                'modified_by' => $userId
            ];

            Product::where('id', $request->input('product_id'))->update($update);

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
