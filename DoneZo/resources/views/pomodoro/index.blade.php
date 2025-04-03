<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pomodoro Timer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .tomato-container {
            position: relative;
            width: 160px;
            height: 160px;
            background: radial-gradient(circle, #E50046 30%, #C4003D);
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .tomato-bite {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #FFF0BD;
            transform-origin: top;
            transform: scaleY(0);
            transition: transform 1s linear;
        }
        .tomato-leaf {
            position: absolute;
            top: -20px;
            left: 50%;
            width: 60px;
            height: 40px;
            background: green;
            border-radius: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body class="flex flex-col justify-center items-center min-h-screen bg-yellow-100 p-4">
    <div class="fixed top-4 left-4">
        <a href="{{ route('dashboard') }}" class="text-[#E50046] hover:text-[#C4003D]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
    </div>
    <div class="relative flex flex-col items-center">
        <div class="relative mb-4">
            <div class="tomato-leaf"></div>
            <div class="tomato-container">
                <div id="tomatoBite" class="tomato-bite"></div>
            </div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-lg w-96 text-center">
            <h1 class="text-3xl font-bold text-red-500">Pomodoro Timer</h1>
            <div class="flex justify-center gap-2 mt-4">
                <button id="pomodoroBtn" class="mode-btn bg-green-300 hover:bg-green-400 active:bg-green-500 px-4 py-2 rounded-full transition">Pomodoro</button>
                <button id="shortBreakBtn" class="mode-btn bg-pink-300 hover:bg-pink-400 active:bg-pink-500 px-4 py-2 rounded-full transition">Short Break</button>
                <button id="longBreakBtn" class="mode-btn bg-blue-300 hover:bg-blue-400 active:bg-blue-500 px-4 py-2 rounded-full transition">Long Break</button>
            </div>
            <div class="mt-6 text-6xl font-bold text-gray-700" id="timerDisplay">25:00</div>
            <div class="flex justify-center gap-3 mt-4">
                <button id="startBtn" class="btn bg-green-500 hover:bg-green-600 px-5 py-2 text-white rounded-full shadow-md">Start</button>
                <button id="pauseBtn" class="btn bg-yellow-500 hover:bg-yellow-600 px-5 py-2 text-white rounded-full shadow-md">Pause</button>
                <button id="resetBtn" class="btn bg-red-500 hover:bg-red-600 px-5 py-2 text-white rounded-full shadow-md">Reset</button>
            </div>
        </div>
    </div>

    <audio id="alarmSound" src="https://www.soundjay.com/button/beep-07.wav"></audio>

    <script>
        let timeLeft = 1500, timer, mode = "pomodoro", pomodoroCount = 0;
        const modes = { pomodoro: 1500, shortBreak: 300, longBreak: 1200 };
        const display = document.getElementById("timerDisplay");
        const buttons = document.querySelectorAll(".mode-btn");
        const tomatoBite = document.getElementById("tomatoBite");
        const alarmSound = document.getElementById("alarmSound");

        function updateDisplay() {
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            display.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            let percentage = ((modes[mode] - timeLeft) / modes[mode]);
            tomatoBite.style.transform = `scaleY(${percentage})`;
        }

        function switchMode(newMode) {
            clearInterval(timer);
            timer = null;
            mode = newMode;
            timeLeft = modes[newMode];
            updateDisplay();
            buttons.forEach(btn => btn.classList.remove("ring-4", "ring-offset-2", "ring-red-400"));
            document.getElementById(`${newMode}Btn`).classList.add("ring-4", "ring-offset-2", "ring-red-400");
        }

        function startTimer() {
            if (!timer) {
                timer = setInterval(() => {
                    if (timeLeft > 0) {
                        timeLeft--;
                        updateDisplay();
                    } else {
                        clearInterval(timer);
                        timer = null;
                        alarmSound.play();
                        pomodoroCount++;
                        if (mode === "pomodoro" && pomodoroCount % 4 === 0) {
                            switchMode("longBreak");
                        } else if (mode === "pomodoro") {
                            switchMode("shortBreak");
                        } else {
                            switchMode("pomodoro");
                        }
                    }
                }, 1000);
            }
        }

        function pauseTimer() { clearInterval(timer); timer = null; }
        function resetTimer() { clearInterval(timer); timer = null; switchMode(mode); }

        document.getElementById("startBtn").addEventListener("click", startTimer);
        document.getElementById("pauseBtn").addEventListener("click", pauseTimer);
        document.getElementById("resetBtn").addEventListener("click", resetTimer);
        document.getElementById("pomodoroBtn").addEventListener("click", () => switchMode("pomodoro"));
        document.getElementById("shortBreakBtn").addEventListener("click", () => switchMode("shortBreak"));
        document.getElementById("longBreakBtn").addEventListener("click", () => switchMode("longBreak"));

        updateDisplay();
    </script>
</body>
</html>
