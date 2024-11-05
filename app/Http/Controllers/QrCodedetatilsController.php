<?php

namespace App\Http\Controllers;

use App\Models\DistributorValidation;
use App\Models\Otp;
use App\Models\Product;
use App\Models\QrCodeScan;
use Illuminate\Http\Request;
use App\Models\QrCode;
use Carbon\Carbon;
use Log;

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

            // Check if a DistributorValidation record already exists
            $distributorValidation = DistributorValidation::where('distributor_id', $qrCode->user->id)
                ->where('product_id', $qrCode->product->id)
                ->where('qr_code_id', $qrCode->id)
                ->first();

            if ($distributorValidation) {
                // Record exists, append the new external QR code serial number
                $externalQrSerials = json_decode($distributorValidation->external_qr_serial, true);
                if (!in_array($unique_number, $externalQrSerials)) {
                    $externalQrSerials[] = $unique_number; // Append new serial number if not already present
                    $distributorValidation->update([
                        'external_qr_serial' => json_encode($externalQrSerials), // Store updated JSON
                        'quantity_validated' => $distributorValidation->quantity_validated + 1, // Increment count
                    ]);
                }
            } else {
                // Record does not exist, create a new one
                DistributorValidation::create([
                    'distributor_id' => $qrCode->user->id, // Assuming distributor_id is user_id
                    'product_id' => $qrCode->product->id,
                    'qr_code_id' => $qrCode->id,
                    'quantity_validated' => 1, // Assuming 1 unit is validated per scan
                    'validation_date' => Carbon::now()->format('Y-m-d H:i:s'),
                    'inserted_by' => $qrCode->user->id, // Assuming user performs the action
                    'inserted_dt' => Carbon::now()->format('Y-m-d H:i:s'),
                    'external_qr_serial' => json_encode([$unique_number]), // Store the new serial number in JSON format
                ]);
            }
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
    $otp_n = rand(100000, 999999);
    Log::info('Generated OTP:', ['otp' => $otp_n]);


    // Store OTP in the database
    Otp::create([
    'mobile_number' => $request->mobile_number,
    'otp' => $otp_n,
    'expires_at' => Carbon::now()->addMinutes(10),
    'inserted_dt' => Carbon::now(),
    'inserted_by' => 1,
    ]);

    // Prepare the message
    $messages = "'.$otp_n.' is your OTP code for your current transaction request from Wockahrdt Hospital. OTPs are
    secure.";
    $mobile_number = $request->mobile_number;
    Log::info('Preparing to send SMS:', ['mobile' => $request->mobile_number, 'message' => $messages]);

    // Initialize cURL
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://360marketingservice.com/api/v2/SendSMS',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
    "senderId": "WCKRJT",
    "is_Unicode": true,
    "is_Flash": false,
    "schedTime": "",
    "groupId": "",
    "message": "'.$otp_n.' is your OTP code for your current transaction request from Wockahrdt Hostpital. OTPs are
    sec",
    "mobileNumbers": "'.$mobile_number.'",
    "serviceId": "",
    "coRelator": "",
    "linkId": "",
    "principleEntityId": "",
    "templateId": "1107162797203344297",
    "apiKey": "Aah5GM2E4ZE114nk4pyIAr2en2iGjE7oX9+t2s6vFGM=",
    "clientId": "d0550349-807b-4e1c-a163-db40848309cd"
    }',

    CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    // echo $response;

    // Return a view displaying the OTP
    return view('otp.show', [
    'scannedNumber' => $request->input('scannedNumber'),
    'otp' => $otp_n,
    'mobile_number' => $request->mobile_number
    ]);
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

            // Fetch scannedNumber from the request
            $unique_number = $request->input('scannedNumber');

            // Retrieve the QR code record from the database
            $qrCode = QrCode::with('product', 'user')
                            ->whereRaw("JSON_CONTAINS(internal_qr_code, '\"$unique_number\"')")
                            ->orWhereRaw("JSON_CONTAINS(external_qr_code, '\"$unique_number\"')")
                            ->first();

            // Check if the QR code was found
            if (!$qrCode) {
                return redirect()->back()->with('error', 'QR code not found.');
            }

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
                    'internal_serial_no_qr_code' => json_encode($internalQrCodes), // Store as JSON
                    'external_serial_no_qr_code' => json_encode($externalQrCodes), // Store as JSON
                    'inserted_dt' => Carbon::now(),
                    'inserted_by' => $qrCode->id,
                ]);
            } elseif ($isExternal) {
                $qrCode->increment('external_qr_code_scan_count'); // Increment without limit

                // Store scan record
                QrCodeScan::create([
                    'qr_code_id' => $qrCode->id,
                    'type' => 'external',
                    'internal_serial_no_qr_code' => json_encode($internalQrCodes), // Store as JSON
                    'external_serial_no_qr_code' => json_encode($externalQrCodes), // Store as JSON
                    'inserted_dt' => Carbon::now(),
                    'inserted_by' => $qrCode->id,
                ]);

                // ** Store distributor validation record **
                // Check if a DistributorValidation record already exists
                $distributorValidation = DistributorValidation::where('distributor_id', $qrCode->user->id)
                    ->where('product_id', $qrCode->product->id)
                    ->where('qr_code_id', $qrCode->id)
                    ->first();

                if ($distributorValidation) {
                    // Record exists, increment the quantity_validated
                    $distributorValidation->increment('quantity_validated', 1);
                } else {
                    // Record does not exist, create a new one
                    DistributorValidation::create([
                        'distributor_id' => $qrCode->user->id, // Assuming distributor_id is user_id
                        'product_id' => $qrCode->product->id,
                        'qr_code_id' => $qrCode->id,
                        'quantity_validated' => 1, // Assuming 1 unit is validated per scan
                        'validation_date' => Carbon::now(),
                        'inserted_by' => $qrCode->user->id, // Assuming user performs the action
                        'inserted_dt' => Carbon::now(),
                        'external_qr_serial' => json_encode([$unique_number]), // Store the external QR code serial number in JSON format
                    ]);
                }
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

            return view('qrcode.show', $viewData)->with('success', 'OTP verified successfully!');
        } else {
            // OTP is incorrect
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    }

    public function showPdf(string $unique_number)
    {
        $pdfFilePath = public_path('qr-codes/pdfs/' . $unique_number . '_QR_codes.pdf');

        // Check if the file exists
        if (file_exists($pdfFilePath)) {
            return response()->file($pdfFilePath); // Serve the file
        }

        return redirect()->back()->with('error', 'PDF not found.');
    }

    public function validationDoneByDistributorList (Request $request)
    {
        // === Fetch DistributorValidation
        $distributorValidations = DistributorValidation::with(
            'distributor', 'product')->orderBy('id', 'DESC')->whereNull('deleted_at')->get();

        return view('report.validation_done_by_distributor_list', [
            'distributorValidations' => $distributorValidations
        ]);
    }

    public function distributorListProductWise(Request $request, $productId)
    {
        // Fetch Product Name
        $product = Product::where('id', $productId)
            ->whereNull('deleted_at')
            ->first();
        // Get Product Name
        $productName = $product->name;

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Fetch DistributorValidation records based on the product_id
        $distributorValidations = DistributorValidation::with('distributor', 'product')
            ->where('product_id', $productId)
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

        // Check if any records were found
        if ($distributorValidations->isEmpty()) {
            return redirect()->back()->with('error', 'No distributor validations found for the specified product.');
        }

        return view('report.distributor_list_product_wise', [
            'distributorValidations' => $distributorValidations,
            'productName' => $productName
        ]);
    }

}
