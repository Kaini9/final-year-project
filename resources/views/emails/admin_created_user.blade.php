<!DOCTYPE html>
<html>
<head>
    <title>Welcome to FashionConnect</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-w-xl mx-auto p-4">
    <div style="text-align: center; margin-bottom: 20px;">
        <h1 style="text-transform: uppercase; letter-spacing: 2px; font-weight: bold; margin: 0;">FashionConnect</h1>
    </div>

    <p>Hi {{ $user->name }},</p>
    
    <p>An administrator has invited you to join FashionConnect! Your account has been created and verified successfully. You can use the credentials below to log in immediately.</p>
    
    <div style="background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; margin: 20px 0;">
        <p style="margin: 0 0 10px 0;"><strong>Email:</strong> {{ $user->email }}</p>
        <p style="margin: 0;"><strong>Password:</strong> <span style="font-monospace: monospace; background: #e5e7eb; padding: 2px 6px;">{{ $passwordText }}</span></p>
    </div>
    
    <p>Please log in and update your password from your account settings as soon as possible for security reasons.</p>
    
    <p style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/login') }}" style="display: inline-block; padding: 10px 20px; background-color: #000; color: #fff; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">Log In Now</a>
    </p>

    <div style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px; text-align: center; font-size: 12px; color: #999;">
        <p>If you did not request this invitation, please ignore this email.</p>
    </div>
</body>
</html>
