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
            /* margin: 0;
            padding: 0; */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* margin: 50px 0; */
        }

        td {
            width: 50%;
            /* padding: 20px; */
            vertical-align: top;
            text-align: center;
        }

        .qr-code-container h1 {
            font-size: 24px;
            /* margin-bottom: 10px; */
        }

        .qr-code-container p {
            /* margin: 5px 0; */
        }

        img {
            width: 150px;
            height: 150px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <table>
        @foreach ($internalQRCodes as $index => $internalQRCode)
        <tr>
            <!-- Internal QR Code Section -->
            <td class="qr-code-container">
                <p>Internal QR Code {{ $index + 1 }}</p>
                {{-- <p><strong>Name:</strong> {{ $user->name }}</p> --}}
                {{-- <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Unique Number:</strong> {{ $internalQRCode['unique_number'] }}</p> --}}
                <img src="data:image/png;base64, {!! base64_encode($internalQRCode['qr_code']) !!}" alt="Internal QR Code">
            </td>

            <!-- External QR Code Section -->
            <td class="qr-code-container">

                <p>External QR Code {{ $index + 1 }}</p>
                {{-- <p><strong>Name:</strong> {{ $user->name }}</p> --}}
                {{-- <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Unique Number:</strong> {{ $externalQRCodes[$index]['unique_number'] }}</p> --}}
                <img src="data:image/png;base64, {!! base64_encode($externalQRCodes[$index]['qr_code']) !!}" alt="External QR Code">
            </td>
        </tr>
        <br>
            @if ($index + 1 < count($internalQRCodes))
                <div class="page-break"></div>
            @endif
        @endforeach
    </table>
</body>

</html>
