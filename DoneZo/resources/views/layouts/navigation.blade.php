<nav x-data="{ open: false }" class="bg-[#C7DB9C]/90 backdrop-blur-sm shadow-md sticky top-0 z-50">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap');
    </style>

    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-12">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="DoneZo Logo" class="h-8 w-auto">
                <span class="text-[#5A702B] text-xl font-fredoka font-semibold">DoneZo</span>
            </div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                    class="bg-[#E50046] hover:bg-[#C4003D] text-white font-['Fredoka'] font-semibold px-5 py-1.5 rounded-full transition-all duration-300 transform hover:scale-105 text-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>
