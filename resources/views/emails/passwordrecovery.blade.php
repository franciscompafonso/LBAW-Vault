<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
</head>
<body class="font-sans bg-gray-50 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-center text-gray-800 mb-4">Password Recovery for Lend-A-Hand</h1>
        <p class="text-gray-700">Hello {{ $username }},</p>
        <p class="text-gray-700 mt-2">We received a request to reset your password. To reset it, click the link below:</p>
        <p class="mt-4">
            <a href="{{ route('password.reset', $token) }}" class="text-blue-500 underline">Reset Your Password</a>
        </p>
        <p class="text-gray-700 mt-4">If you did not request a password reset, please ignore this email.</p>
    </div>
</body>
</html>
