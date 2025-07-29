<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kode OTP - Boint</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #0059ff;
            letter-spacing: 4px;
            text-align: center;
            margin: 30px 0;
        }
        .name{
            text-transform: capitalize;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login your email address</h2>

        <p class='name'>Hello,</p>

        <p>Your account login code is :</p>

        <div class="otp-code">{{ $otp }}</div>

        <p>
            Immediately type the code in the <strong>Boint</strong> application and it will automatically expire in 
            <strong>2 minutes</strong> if not used. If this code doesn't work, please press the button 
            "<strong>Resend</strong>."
        </p>

        <p>Thank you,<br>
        <strong>rimou.site</strong></p>

        <div class="footer">
            *This code automatically becomes invalid when:
            <ol>
                <li>(1) You have used this code</li>
                <li>(2) You request a new code from the Boint website</li>
                <li>(3) After 2 minutes if not used</li>
            </ol>
        </div>
    </div>
</body>
</html>
