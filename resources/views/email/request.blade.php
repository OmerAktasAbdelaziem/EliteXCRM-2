<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cabin', sans-serif;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
        }
        .email-header {
            background-color: #333333;
            padding: 20px;
            text-align: center;
        }
        .email-header img {
            max-width: 250px;
        }
        .email-body {
            padding: 30px;
        }
        .email-body h2 {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333333;
        }
        .email-body p {
            font-size: 14px;
            color: #666666;
            line-height: 1.6;
        }
        .email-body .details {
            background-color: #f4f4f4;
            border: 1px solid #cccccc;
            padding: 10px;
            margin: 20px 0;
        }
        .details table {
            width: 100%;
        }
        .details td {
            padding: 10px;
            font-size: 14px;
        }
        .details td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .email-footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999999;
        }
        .email-footer a {
            color: #333333;
            text-decoration: none;
        }
        .ar, .ar * {
            font-family: 'Noto Kufi Arabic', sans-serif;
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="email-container">
    <!-- Header -->
    <div class="email-header">
        <img src="{{$data['logo_url']}}">
    </div>
    <div class="email-body">
        <h2>You have a new request</h2>
        <!-- Login Details -->
        <div class="details">
            <table>
                <tr>
                    <td>Client ID :</td>
                    <td>{{$data['client']}}</td>
                </tr>
                <tr>
                    <td>Amount :</td>
                    <td>{{$data['amount']}}</td>
                </tr>
                <tr>
                    <td>Type :</td>
                    <td>{{$data['reqType']}}</td>
                </tr>
                <tr>
                    <td>Comment :</td>
                    <td>{{$data['comment']}}</td>
                </tr>
                <tr>
                    <td>Bank/Branch :</td>
                    <td>
                        @isset($data['paymentDetailsInfo']['iban'])    
                            Swift : {{$data['paymentDetailsInfo']['swift']}}
                            <br>
                            Iban : {{$data['paymentDetailsInfo']['iban']}}
                            <br>
                            Bank Name : {{$data['paymentDetailsInfo']['bankName']}}
                            <br>
                            Bank Country : {{$data['paymentDetailsInfo']['bankCountry']}}
                            <br>
                            Bank Address : {{$data['paymentDetailsInfo']['bankAddress']}}
                            <br>
                            Beneficiary Name : {{$data['paymentDetailsInfo']['beneficiaryName']}}
                            <br>
                            Beneficiary Country : {{$data['paymentDetailsInfo']['beneficiaryCountry']}}
                            <br>
                            Beneficiary Address : {{$data['paymentDetailsInfo']['beneficiaryAddress']}}
                            <br>
                            AbaRouting Number : {{$data['paymentDetailsInfo']['abaRoutingNumber']}}
                        @endisset

                        @isset($data['paymentDetailsInfo']['cryptoWalletAddress'])    
                            CryptoWalletAddress : {{$data['paymentDetailsInfo']['cryptoWalletAddress']}}
                            <br>
                            Withdrawal Option : {{$data['paymentDetailsInfo']['withdrawalOption']}}
                            <br>
                            Transaction Hash : {{$data['paymentDetailsInfo']['transactionHash']}}
                        @endisset

                        @isset ($data['paymentDetailsInfo']['beneficiaryCountry'])
                            {{ $data['paymentDetailsInfo']['beneficiaryCountry']??'' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Date/Time :</td>
                    <td>{{$data['created_at']}}</td>
                </tr>
            </table>
        </div>

    </div>

    

    <!-- Footer -->
    <div class="email-footer">
        &copy; 2024 {{$data['company']}}. All rights reserved.
    </div>
</div>

</body>
</html>
