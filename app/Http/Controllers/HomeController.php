<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\QrCode;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome(Request $request)
    {
        // === Total Sum of Product Validated by Distributor in internal_qr_code_count
        $totalNumberProductValidateCount =QrCode::where('internal_qr_code_count', '>', 0)->count();

        // === Total Sum of Product Validated by Doctor in external_qr_code_count
        $totalNumberDoctorValidateCount =QrCode::where('external_qr_code_count', '>', 0)->count();

        return view('home',[
            'totalNumberProductValidateCount' => $totalNumberProductValidateCount,
            'totalNumberDoctorValidateCount' => $totalNumberDoctorValidateCount
        ]);
    }

    public function fetchAvilableQuantity(Request $request)
    {
        // Get the product_id from the request
        $productId = $request->productId;

        // Fetch QR codes for the given product
        $qrCodes = QrCode::where('product_id', $productId)->get();

        // Prepare arrays for internal and external QR codes
        $internalQrCodes = [];
        $externalQrCodes = [];

        foreach ($qrCodes as $qrCode) {
            // Decode JSON data
            $internalCodes = json_decode($qrCode->internal_qr_code, true);
            $externalCodes = json_decode($qrCode->external_qr_code, true);

            // Merge into arrays
            if (is_array($internalCodes)) {
                $internalQrCodes = array_merge($internalQrCodes, $internalCodes);
            }
            if (is_array($externalCodes)) {
                $externalQrCodes = array_merge($externalQrCodes, $externalCodes);
            }
        }

        return response()->json([
            'available_quantity' => count($internalQrCodes), // Count of internal QR codes
            'external_qr_codes' => $externalQrCodes, // Include the external QR codes in the response
        ]);
    }
}
