<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DoneZo!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap');

        body {
            font-family: 'Fredoka', sans-serif;
        }

        .bg-cute {
            background-image: url('https://wallpapercave.com/uwp/uwp1772743.gif');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-cute min-h-screen flex items-center justify-center px-4">
    <div class="bg-white bg-opacity-90 backdrop-blur-md p-8 md:p-12 rounded-3xl shadow-[0_10px_25px_rgba(229,0,70,0.3)] border-4 border-[#FDAB9E] max-w-xl text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold text-[#E50046] drop-shadow-md mb-4">✨ DoneZo ✨</h1>
        <p class="text-lg md:text-xl text-gray-700 mb-6">
            Your cutest productivity buddy! 🐣<br>
            Stay on top of your tasks with a to-do list and Pomodoro timer that’s as adorable as it is powerful.
        </p>
        <a href="{{ route('login') }}" class="bg-[#C7DB9C] hover:bg-[#B8CD8F] text-[#E50046] font-bold px-6 py-3 rounded-full transition shadow-md">
            Get Started 💼
        </a>
    </div>
</body>
</html>
