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
        // ==== Fetch QrCode
        $qrcodes = QrCode::with('user', 'product')->whereNull('deleted_at')->orderBy('id', 'desc')->get();

        return view('qrcode.index', [
            'qrcodes' => $qrcodes
        ]);
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

            // Assuming the product details are fetched based on product_id
            $product = Product::find($request->input('product_id'));

            // Get current date in YYYYMMDD format (8 characters)
            $date = Carbon::now()->format('Ymd');

            // Path to save QR code images in the public folder
            $internalQrPath = public_path('qr-codes/internal/');
            $externalQrPath = public_path('qr-codes/external/');
            $pdfPath = public_path('qr-codes/pdfs/'); // Define PDF path

            // Ensure the directories exist
            if (!file_exists($internalQrPath)) {
                mkdir($internalQrPath, 0755, true);
            }
            if (!file_exists($externalQrPath)) {
                mkdir($externalQrPath, 0755, true);
            }
            if (!file_exists($pdfPath)) { // Ensure PDF directory exists
                mkdir($pdfPath, 0755, true);
            }

            // Loop through the quantity and generate internal/external QR codes
            for ($i = 0; $i < $quantity; $i++) {

                // Generate a random 4-digit number to enhance uniqueness
                $randomNumberInternal = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $randomNumberExternal = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

                // Include microseconds to further ensure uniqueness
                $microtime = str_pad((int)(microtime(true) * 1000), 13, '0', STR_PAD_LEFT);

                // Combine product ID, date, random numbers, and microtime for more uniqueness
                $uniqueInternalNumber = $date . $product->id . $randomNumberInternal . substr($microtime, -4);
                $uniqueExternalNumber = $date . $product->id . $randomNumberExternal . substr($microtime, -4);

                // Ensure these numbers are still manageable in length, adjust if needed
                $uniqueInternalNumber = substr($uniqueInternalNumber, 0, 16);
                $uniqueExternalNumber = substr($uniqueExternalNumber, 0, 16);

                // Define QR code size (2 cm = 76 pixels)
                $qrCodeSize = 76;

                // File names for the QR code images
                $internalQrFileName = $uniqueInternalNumber . '.png';
                $externalQrFileName = $uniqueExternalNumber . '.png';

                // Full paths to save the QR codes
                $internalQrFullPath = $internalQrPath . $internalQrFileName;
                $externalQrFullPath = $externalQrPath . $externalQrFileName;

                // Generate and save Internal QR Code as an image
                GenerateQrCode::size($qrCodeSize)
                    ->format('png')
                    ->generate(route('qr.show', ['unique_number' => $uniqueInternalNumber]), $internalQrFullPath);

                // Generate and save External QR Code as an image
                GenerateQrCode::size($qrCodeSize)
                    ->format('png')
                    ->generate(route('qr.show', ['unique_number' => $uniqueExternalNumber]), $externalQrFullPath);

                // Append to internal and external QR code arrays
                $internalQRCodes[] = [
                    'qr_code_image_name' => $internalQrFileName,
                    'unique_number' => $uniqueInternalNumber,
                    'status' => 'active',
                    'printed_date' => Carbon::now()->toDateString() // Add the printed date
                ];

                $externalQRCodes[] = [
                    'qr_code_image_name' => $externalQrFileName,
                    'unique_number' => $uniqueExternalNumber,
                    'status' => 'active',
                    'printed_date' => Carbon::now()->toDateString() // Add the printed date
                ];
            }

            // Save the QR code details in the database
            $qrCode = new QrCode();
            $qrCode->unique_number = uniqid();
            $qrCode->user_id = $userId;
            $qrCode->quantity = $quantity;
            $qrCode->product_id = $request->input('product_id');
            $qrCode->internal_qr_code = json_encode(array_column($internalQRCodes, 'unique_number'));
            $qrCode->external_qr_code = json_encode(array_column($externalQRCodes, 'unique_number'));
            $qrCode->internal_qr_code_count = count($internalQRCodes);
            $qrCode->external_qr_code_count = count($externalQRCodes);
            $qrCode->internal_qr_code_status = 'active';
            $qrCode->external_qr_code_status = 'active';
            $qrCode->internal_qr_code_images = json_encode(array_column($internalQRCodes, 'qr_code_image_name')); // Store internal QR code image names
            $qrCode->external_qr_code_images = json_encode(array_column($externalQRCodes, 'qr_code_image_name')); // Store external QR code image names
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

            // return $pdf->stream($qrCode->unique_number . '_QR_codes.pdf');

            // Save PDF to the defined path
            $pdfFilePath = $pdfPath . $qrCode->unique_number . '_QR_codes.pdf';
            $pdf->save($pdfFilePath);

            // Return a response with a script to open the PDF and redirect with success message
            return redirect()->route('qrcode.index', ['pdfUrl' => asset('qr-codes/pdfs/' . $qrCode->unique_number . '_QR_codes.pdf')])->header('Content-Type', 'text/html')->with('message', 'QR codes created successfully.');

        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something went wrong - ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $unique_number)
    {
        // Retrieve the QR code record from the database
        $qrCode = QrCode::where('unique_number', $unique_number)->first();

        if (!$qrCode) {
            return redirect()->back()->with('error', 'QR code not found.');
        }

        // Return a view with the details of the QR code in form method
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
        // Get the current user's ID for tracking deletions
        $data['deleted_by'] = Auth::user()->id;
        $data['deleted_at'] = Carbon::now();

        try {
            // Find the QR code entry
            $qrCode = QrCode::findOrFail($id);

            // Get the image names and PDF file path
            $internalQrCodes = json_decode($qrCode->internal_qr_code_images);
            $externalQrCodes = json_decode($qrCode->external_qr_code_images);
            $pdfFilePath = public_path('qr-codes/pdfs/' . $qrCode->unique_number . '_QR_codes.pdf');

            // Define paths to the internal and external QR code images
            $internalQrPath = public_path('qr-codes/internal/');
            $externalQrPath = public_path('qr-codes/external/');

            // Delete internal QR code images
            foreach ($internalQrCodes as $imageName) {
                $imagePath = $internalQrPath . $imageName;
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the file
                }
            }

            // Delete external QR code images
            foreach ($externalQrCodes as $imageName) {
                $imagePath = $externalQrPath . $imageName;
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the file
                }
            }

            // Delete the PDF file
            if (file_exists($pdfFilePath)) {
                unlink($pdfFilePath); // Delete the PDF
            }

            // Delete the QR code record permanently
            $qrCode->delete();

            return redirect()->route('qrcode.index')->with('message', 'Your record has been successfully deleted.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

}
