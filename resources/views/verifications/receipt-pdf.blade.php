<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payment Receipt - FashionConnect</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 40px;
            font-size: 14px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #5C2D91;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #5C2D91;
            margin: 0 0 10px 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .receipt-info {
            width: 100%;
            margin-bottom: 40px;
        }
        .receipt-info td {
            vertical-align: top;
        }
        .receipt-info .right {
            text-align: right;
        }
        h2 {
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 5px;
            color: #111;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .details-table th, .details-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        .details-table th {
            background-color: #fcfcfc;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .details-table td {
            font-weight: bold;
        }
        .amount-row td {
            font-size: 18px;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            background-color: #fafafa;
        }
        .footer {
            text-align: left;
            margin-top: 60px;
            color: #888;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            clear: always;
        }
        .status-badge {
            background-color: #d1fae5;
            color: #065f46;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>FashionConnect</h1>
        <p>Payment Receipt</p>
    </div>

    <table class="receipt-info">
        <tr>
            <td>
                <h2>Billed To:</h2>
                <p><strong>{{ $verification->user->name }}</strong><br>
                {{ $verification->user->email }}<br>
                {{ $verification->user->role->name }}</p>
            </td>
            <td class="right">
                <p><strong>Receipt Date:</strong> {{ $verification->updated_at->format('F j, Y') }}</p>
                <p><strong>Transaction ID:</strong> {{ $verification->khalti_pidx }}</p>
                <div class="status-badge">PAID VIA KHALTI</div>
            </td>
        </tr>
    </table>

    <table class="details-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Verified Badge Application Fee (1 Month Validity)</td>
                <td style="text-align: right;">Rs. 200.00</td>
            </tr>
            <tr class="amount-row">
                <td style="text-align: right;">TOTAL PAID</td>
                <td style="text-align: right;">Rs. 200.00</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer generated receipt and does not require a physical signature.</p>
        <p>If you have any questions concerning this receipt, contact support at <strong>support@fashionconnect.com</strong>.</p>
        <p>&copy; {{ date('Y') }} FashionConnect. All rights reserved.</p>
    </div>

</body>
</html>
