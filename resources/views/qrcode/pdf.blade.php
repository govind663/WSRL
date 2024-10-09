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

        .qr-code-wrapper {
            padding: 35px;
            display: inline-block;
            width: 150px;
            height: 150px;
            border: 6px solid #007bff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .qr-code-wrapper-external {
            padding: 10px;
            display: inline-block;
            width: 190px;
            height: 190px;
            border: 6px solid #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        img {
            width: 100%;
            height: auto;
        }

        .qr-code-wrapper img {
            width: auto;
            height: 100%;
            object-fit: contain;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @foreach ($internalQRCodes as $index => $internalQRCode)
    <table>
        <tr>
            <td class="qr-code-container">
                <div class="qr-code-wrapper">
                    <img src="{{ asset('qr-codes/internal/' . $internalQRCode['unique_number'] . '.png') }}" alt="Internal QR Code">
                </div>
                <p style="text-align: left !important;">Serial Number: {{ $internalQRCode['unique_number'] }}</p>
            </td>

            <td class="qr-code-container">
                <div class="qr-code-wrapper-external">
                    <img src="{{ asset('qr-codes/external/' . $externalQRCodes[$index]['unique_number'] . '.png') }}" alt="External QR Code">
                </div>
                <p style="text-align: left !important;">Serial Number: {{ $externalQRCodes[$index]['unique_number'] }}</p>
            </td>
        </tr>
    </table>
    @endforeach
</body>

</html>
