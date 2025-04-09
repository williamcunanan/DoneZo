<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DoneZo</title>
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
        <h2 class="text-3xl font-bold text-[#E50046] mb-6">Create Your DoneZo Account</h2>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4 text-left">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-gray-700 font-medium">Name</label>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                    class="w-full px-4 py-2 rounded-full border border-gray-300 focus:ring-2 focus:ring-[#FDAB9E] focus:outline-none">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                    class="w-full px-4 py-2 rounded-full border border-gray-300 focus:ring-2 focus:ring-[#FDAB9E] focus:outline-none">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-gray-700 font-medium">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-4 py-2 rounded-full border border-gray-300 focus:ring-2 focus:ring-[#FDAB9E] focus:outline-none">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-gray-700 font-medium">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-4 py-2 rounded-full border border-gray-300 focus:ring-2 focus:ring-[#FDAB9E] focus:outline-none">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Register Button -->
            <div>
                <button type="submit"
                    class="w-full bg-[#FDAB9E] hover:bg-[#FC8C7A] text-white font-bold py-2 rounded-full transition-all shadow-md">Register</button>
            </div>
        </form>

        <p class="mt-4 text-sm text-gray-700">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[#E50046] font-semibold hover:underline">Login</a>
        </p>
    </div>
</body>

</html>
