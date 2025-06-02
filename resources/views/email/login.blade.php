<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Details</title>
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

    <!-- Body -->
    <div class="email-body ar">
        <h2>معلومات تسجيل الدخول</h2>
        <p>{{$data['name']}}</p>
        <p>نشكرك على اهتمامك بفتح حساب حقيقي مع {{$data['company']}}!</p>
        <p>يرجى تسجيل الدخول إلى غرفة المتداولين لإتمام عملية تسجيل الحساب وإدارة حسابك.</p>

        <!-- Login Details -->
        <div class="details">
            <table>
                <tr>
                    <td>اسم المستخدم:</td>
                    <td>{{$data['username']}}</td>
                </tr>
                <tr>
                    <td>كلمة السر:</td>
                    <td>{{$data['password']}}</td>
                </tr>
                <tr>
                    <td>صفحة تسجيل الدخول:</td>
                    <td><a href="{{$data['company_url']}}">{{$data['company']}}</a></td>
                </tr>
            </table>
        </div>

        <p>إذا كنت بحاجة إلى أي مساعدة، فلا تتردد في الاتصال بفريق الدعم الخاص بنا وسنساعدك بكل سرور.</p>
    </div>
    <div class="email-body">
        <h2>Login details</h2>
        <p>Dear {{$data['name']}},</p>
        <p>Thank you for your interest in opening a Live account with {{$data['company']}}!</p>
        <p>Please login to Traders Room to finalize the account registration and manage your account.</p>

        <!-- Login Details -->
        <div class="details">
            <table>
                <tr>
                    <td>Login:</td>
                    <td>{{$data['username']}}</td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td>{{$data['password']}}</td>
                </tr>
                <tr>
                    <td>Login page:</td>
                    <td><a href="{{$data['company_url']}}">{{$data['company']}}</a></td>
                </tr>
            </table>
        </div>

        <p>If you need any help, do not hesitate to contact our support and we will gladly assist you.</p>
    </div>

    

    <!-- Footer -->
    <div class="email-footer">
        &copy; 2024 {{$data['company']}}. All rights reserved.
    </div>
</div>

</body>
</html>
