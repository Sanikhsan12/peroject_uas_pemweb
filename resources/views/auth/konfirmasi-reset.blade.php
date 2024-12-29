<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Reset Password</h2>
    <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
    <p>token Anda: {{ $token }}</p>
    <p>Email Anda: {{ $email }}</p>
    <p>Silakan klik tombol di bawah ini untuk reset password:</p>
    
    <div style="margin: 30px 0;">
        <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}"
            style="background-color: #4CAF50; color: white; padding: 15px 25px; text-decoration: none; border-radius: 4px;">
            Reset Password
        </a>
    </div>

    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
    <p>Link reset password ini akan kadaluarsa dalam 60 menit.</p>

    <p>Salam,<br>Tim Perpustakaan Digital</p>
</body>
</html>