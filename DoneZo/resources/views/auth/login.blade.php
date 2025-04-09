<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DoneZo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('https://i.pinimg.com/736x/1f/7f/f5/1f7ff56b471f2bcb30cac89bdc4d67c9.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">
    <div class="bg-white/70 backdrop-blur-md p-8 rounded-3xl shadow-2xl w-full max-w-md text-center border-2 border-[#FDAB9E]">
        <h2 class="text-3xl font-bold text-[#E50046] mb-6">Welcome Back to DoneZo!</h2>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-left text-gray-700 font-medium">Email</label>
                <input id="email" type="email" name="email" required autofocus
                    class="w-full px-4 py-2 rounded-full border border-gray-300 focus:ring-2 focus:ring-[#FDAB9E] focus:outline-none">
            </div>

            <div>
                <label for="password" class="block text-left text-gray-700 font-medium">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-2 rounded-full border border-gray-300 focus:ring-2 focus:ring-[#FDAB9E] focus:outline-none">
            </div>

            <div class="text-right">
                @if (Route::has('password.request'))
                    <a class="text-sm text-[#E50046] hover:underline" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-[#FDAB9E] hover:bg-[#FC8C7A] text-white font-bold py-2 rounded-full transition-all shadow-md">Login</button>
            </div>
        </form>

        <p class="mt-4 text-sm text-gray-700">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[#E50046] font-semibold hover:underline">Register</a>
        </p>
    </div>
</body>

</html>
