<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FAF8F5; color: #1A1A1A; padding: 40px; margin: 0; }
        .container { max-width: 500px; margin: 0 auto; background: #FFFFFF; padding: 40px; border: 1px solid #E8E4DF; }
        .logo { font-family: 'Bebas Neue', sans-serif; font-size: 32px; letter-spacing: 0.1em; text-align: center; margin-bottom: 40px; }
        .code { font-size: 36px; font-weight: 700; text-align: center; letter-spacing: 0.5em; padding: 20px 0; border-top: 1px solid #E8E4DF; border-bottom: 1px solid #E8E4DF; margin: 30px 0; }
        .text { font-size: 15px; line-height: 1.6; color: #6B6B6B; }
        .footer { margin-top: 40px; font-size: 12px; color: #A3A3A3; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">FASHIONCONNECT</div>
        <p class="text">Hi {{ $user->name }},</p>
        <p class="text">Please use the following single-use verification code to authenticate your account. This code is valid for 10 minutes.</p>
        
        <div class="code">{{ $code }}</div>
        
        <p class="text">If you did not request this code, you can safely ignore this email.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} FashionConnect. All rights reserved.
        </div>
    </div>
</body>
</html>
