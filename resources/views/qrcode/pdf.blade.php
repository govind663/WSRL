<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 10px;
        }

        .qr-code-container h1 {
            font-size: 18px;
            text-align: left;
            padding-left: 20px;
        }

        /* Circular border for internal QR code with blue border */
        .qr-code-wrapper {
            padding: 35px;
            display: inline-block;
            width: 150px;
            height: 150px;
            border: 6px solid #007bff; /* Blue circular border */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden; /* Ensure the image stays inside the circle */
        }

        /* Square border for external QR code with green border */
        .qr-code-wrapper-external {
            padding: 10px;
            display: inline-block;
            width: 190px;
            height: 190px;
            border: 6px solid #28a745; /* Green square border */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Ensure the QR code image fits the container properly */
        img {
            width: 100%;
            height: auto; /* Maintain the original aspect ratio of the QR code */
        }

        /* For images inside the circular QR container */
        .qr-code-wrapper img {
            width: auto;
            height: 100%;
            object-fit: contain; /* Ensure the image fits within the circle without reducing its size unnecessarily */
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <table>
        @foreach ($internalQRCodes as $index => $internalCode)
        <tr>
            <!-- Internal QR Code Section -->
            <td class="qr-code-container">
                <div class="qr-code-wrapper">
                    <img src="data:image/png;base64, {!! base64_encode(file_get_contents(public_path('qr-codes/internal/' . $internalCode['qr_code_image_name']))) !!}" alt="Internal QR Code">
                </div>
                <p style="text-align: left !important;">
                    Serial Number: {{ $internalCode['unique_number'] }}
                </p>
            </td>
            <!-- External QR Code Section -->
            <td class="qr-code-container">
                @if(isset($externalQRCodes[$index])) <!-- Check if external code exists -->
                <div class="qr-code-wrapper-external">
                    <img src="data:image/png;base64, {!! base64_encode(file_get_contents(public_path('qr-codes/external/' . $externalQRCodes[$index]['qr_code_image_name']))) !!}" alt="External QR Code">
                </div>
                <p style="text-align: left !important;">
                    Serial Number: {{ $externalQRCodes[$index]['unique_number'] }}
                </p>
                @else
                <p>No External QR Code</p> <!-- Message if no external QR code is available -->
                @endif
            </td>
        </tr>
        <!--<tr>-->

        <!--</tr>-->
        @endforeach
    </table>
</body>

</html>
