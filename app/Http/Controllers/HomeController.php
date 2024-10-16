<?php

namespace App\Http\Controllers;

use App\Models\Dispatch;
use App\Models\Distributor;
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
        $totalNumberProductValidateCount = QrCode::where('internal_qr_code_scan_count', '>', 0)->sum('internal_qr_code_scan_count');

        // === Total Sum of Product Validated by Doctor in external_qr_code_count
        $totalNumberDoctorValidateCount = QrCode::where('external_qr_code_scan_count', '>', 0)->sum('external_qr_code_scan_count');

        return view('home',[
            'totalNumberProductValidateCount' => $totalNumberProductValidateCount,
            'totalNumberDoctorValidateCount' => $totalNumberDoctorValidateCount
        ]);
    }

    /**
     * Fetch available quantity
     */
    public function fetchAvilableQuantity(Request $request)
    {
        $productId = $request->productId;
        $distributorId = $request->distributorId; // Include distributor_id in the request

        // Fetch QR codes for the given product
        $qrCodes = QrCode::where('product_id', $productId)->get();

        $internalQrCodes = [];
        $externalQrCodes = [];

        foreach ($qrCodes as $qrCode) {
            $internalCodes = json_decode($qrCode->internal_qr_code, true);
            $externalCodes = json_decode($qrCode->external_qr_code, true);

            if (is_array($internalCodes)) {
                $internalQrCodes = array_merge($internalQrCodes, $internalCodes);
            }
            if (is_array($externalCodes)) {
                $externalQrCodes = array_merge($externalQrCodes, $externalCodes);
            }
        }

        // Fetch already assigned external QR codes for the distributor and product
        $assignedQrCodes = Dispatch::where('distributor_id', $distributorId)
                                    ->where('product_id', $productId)
                                    ->pluck('external_qr_code_serial_number')
                                    ->toArray();

        // Remove assigned external QR codes from the list
        $availableQrCodes = array_diff($externalQrCodes, $assignedQrCodes);

        return response()->json([
            'available_quantity' => count($internalQrCodes),
            'external_qr_codes' => array_values($availableQrCodes),
            'assigned_qr_codes' => $assignedQrCodes, // For reference
        ]);
    }

    /**
     * Check if the external QR codes are already assigned
     */
    public function checkAssignedQrCodes(Request $request)
    {
        $distributorId = $request->distributorId;
        $productId = $request->productId;
        $externalQrCodes = $request->externalQrCodes;

        $assignedQrCodes = Dispatch::where('distributor_id', $distributorId)
                                    ->where('product_id', $productId)
                                    ->whereIn('external_qr_code_serial_number', $externalQrCodes)
                                    ->pluck('external_qr_code_serial_number')
                                    ->toArray();

        return response()->json(['alreadyAssigned' => $assignedQrCodes]);
    }

    public function validationDoneByDistributorList (Request $request)
    {
        $distributors = Distributor::orderBy('id', 'DESC')->whereNull('deleted_at')->get();
        // return $distributors;

        return view('report.validation_done_by_distributor_list', [
            'distributors' => $distributors
        ]);
    }

}
