<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
        }

        td {
            width: 50%;
            vertical-align: top;
            text-align: center;
        }

        .qr-code-container h1 {
            font-size: 24px;
        }

        /* Circular border for internal QR code only */
        .qr-code-wrapper {
            padding-top: 10px;
            padding-left: 10px;
            padding-right: 10px;
            display: inline-block;
            width: 210px;
            height: 210px;
            border: 6px solid rgb(24, 24, 26); /* Blue circular border */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* External image in square */
        .qr-code-wrapper-external {
            padding: 17px;
            display: inline-block;
            width: 210px;
            height: 210px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        img {
            width: 155px;
            height: 160px;
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

            <!-- Internal QR Code Section -->
            <td class="qr-code-container">
                <p style="text-align: left !important; padding-left:20px !important;">Internal QR Code {{ $index + 1 }}</p>
                <div class="col-sm-4 p-3 qr-code-wrapper">
                    <br>
                    <img src="data:image/png;base64, {!! base64_encode($internalQRCode['qr_code']) !!}" alt="Internal QR Code" width="50%" height="50%">
                </div>
            </td>

            <!-- External QR Code Section -->
            <td class="qr-code-container">
                <p style="text-align: left !important; padding-left:20px !important;">External QR Code {{ $index + 1 }}</p>
                <div class="col-sm-4 p-3 qr-code-wrapper-external">
                    <img src="data:image/png;base64, {!! base64_encode($externalQRCodes[$index]['qr_code']) !!}" alt="External QR Code">
                </div>
            </td>
        </tr>
    </table>
    @endforeach
</body>

</html>
