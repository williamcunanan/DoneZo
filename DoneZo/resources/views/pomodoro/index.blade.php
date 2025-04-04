<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pomodoro Timer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

    <div x-data="pomodoroTimer()" x-init="initializeTimers()" class="relative flex flex-col items-center">
        <div class="relative mb-4">
            <div class="tomato-leaf"></div>
            <div class="tomato-container">
                <div id="tomatoBite" class="tomato-bite"></div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-lg w-[480px] text-center">
            <h1 class="text-3xl font-bold text-red-500">Pomodoro Timer</h1>
            <div class="flex justify-center gap-2 mt-4">
                <button @click="switchMode('pomodoro')" 
                    :class="{'ring-4 ring-offset-2 ring-red-400': mode === 'pomodoro'}" 
                    class="mode-btn bg-green-300 hover:bg-green-400 active:bg-green-500 px-4 py-2 rounded-full transition">
                    Pomodoro
                </button>
                <button @click="switchMode('shortBreak')" 
                    :class="{'ring-4 ring-offset-2 ring-red-400': mode === 'shortBreak'}" 
                    class="mode-btn bg-pink-300 hover:bg-pink-400 active:bg-pink-500 px-4 py-2 rounded-full transition">
                    Short Break
                </button>
                <button @click="switchMode('longBreak')" 
                    :class="{'ring-4 ring-offset-2 ring-red-400': mode === 'longBreak'}" 
                    class="mode-btn bg-blue-300 hover:bg-blue-400 active:bg-blue-500 px-4 py-2 rounded-full transition">
                    Long Break
                </button>
            </div>
            
            <!-- Timer Duration Settings -->
            <div class="mt-4 grid grid-cols-3 gap-2">
                <div class="flex flex-col items-center gap-1">
                    <label class="text-sm text-gray-600">Pomodoro</label>
                    <div class="flex items-center gap-1">
                        <input type="number" 
                            min="1" 
                            max="60" 
                            :value="Math.floor(durations.pomodoro / 60)"
                            @change="updateDuration('pomodoro', $event.target.value)"
                            class="w-14 px-2 py-1 text-sm border rounded-lg focus:ring-[#E50046] focus:border-[#E50046] text-center">
                        <span class="text-xs text-gray-600">min</span>
                    </div>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <label class="text-sm text-gray-600">Short Break</label>
                    <div class="flex items-center gap-1">
                        <input type="number" 
                            min="1" 
                            max="30" 
                            :value="Math.floor(durations.shortBreak / 60)"
                            @change="updateDuration('shortBreak', $event.target.value)"
                            class="w-14 px-2 py-1 text-sm border rounded-lg focus:ring-[#E50046] focus:border-[#E50046] text-center">
                        <span class="text-xs text-gray-600">min</span>
                    </div>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <label class="text-sm text-gray-600">Long Break</label>
                    <div class="flex items-center gap-1">
                        <input type="number" 
                            min="1" 
                            max="60" 
                            :value="Math.floor(durations.longBreak / 60)"
                            @change="updateDuration('longBreak', $event.target.value)"
                            class="w-14 px-2 py-1 text-sm border rounded-lg focus:ring-[#E50046] focus:border-[#E50046] text-center">
                        <span class="text-xs text-gray-600">min</span>
                    </div>
                </div>
            </div>

            <!-- Sound Controls -->
            <div class="mt-4 flex justify-center items-center gap-4">
                <button @click="toggleMute" 
                    class="flex items-center gap-2 px-3 py-1 rounded-lg text-sm"
                    :class="isMuted ? 'bg-gray-200 text-gray-600' : 'bg-green-100 text-green-600'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="!isMuted" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                    </svg>
                    <span x-text="isMuted ? 'Unmute' : 'Mute'"></span>
                </button>
                <button x-show="isAlarmPlaying" 
                    @click="stopAlarm" 
                    class="flex items-center gap-2 px-3 py-1 bg-red-100 text-red-600 rounded-lg text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Stop Alarm
                </button>
            </div>

            <div class="mt-6 text-6xl font-bold text-gray-700" x-text="formatTime(timeLeft)">25:00</div>
            <div class="flex justify-center gap-3 mt-4">
                <button @click="startTimer" x-show="!timer" class="btn bg-green-500 hover:bg-green-600 px-5 py-2 text-white rounded-full shadow-md">Start</button>
                <button @click="pauseTimer" x-show="timer" class="btn bg-yellow-500 hover:bg-yellow-600 px-5 py-2 text-white rounded-full shadow-md">Pause</button>
                <button @click="resetTimer" class="btn bg-red-500 hover:bg-red-600 px-5 py-2 text-white rounded-full shadow-md">Reset</button>
            </div>
        </div>

        <!-- Active Tasks Section -->
        <div class="mt-8 w-full max-w-2xl bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-[#E50046] mb-4">Active Tasks</h2>
            <div class="space-y-4">
                @forelse($activeTasks as $task)
                    <div class="group flex items-center justify-between p-4 bg-[#FFF0BD] rounded-xl transition-all duration-300 hover:shadow-md">
                        <div class="flex items-center space-x-4">
                            <input type="checkbox" 
                                class="w-5 h-5 text-[#E50046] rounded border-gray-300 focus:ring-[#E50046] transition-colors"
                                @change="markTaskAsCompleted({{ $task->id }})">
                            <div>
                                <span class="text-gray-700 font-medium group-hover:text-[#E50046] transition-colors">{{ $task->name }}</span>
                                @if($task->description)
                                    <p class="text-sm text-gray-600">{{ $task->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <input type="number" 
                                    min="1" 
                                    max="60" 
                                    value="{{ $task->duration ?? 25 }}"
                                    @change="updateTaskDuration({{ $task->id }}, $event.target.value)"
                                    class="w-16 px-2 py-1 text-sm border rounded-lg focus:ring-[#E50046] focus:border-[#E50046]">
                                <span class="text-sm text-gray-600">min</span>
                            </div>
                            <div class="text-sm font-medium" x-text="formatTime(taskTimers[{{ $task->id }}] || {{ ($task->duration ?? 25) * 60 }})"></div>
                            <button @click="resetTaskTimer({{ $task->id }})" class="text-sm text-[#E50046] hover:text-[#C4003D]">Reset</button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2 text-gray-500">No active tasks</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <audio id="alarmSound" preload="auto">
        <source src="{{ asset('sounds/alarm.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="taskCompleteSound" preload="auto">
        <source src="{{ asset('sounds/complete.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="breakCompleteSound" preload="auto">
        <source src="{{ asset('sounds/break.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="startSound" preload="auto">
        <source src="https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3" type="audio/mpeg">
    </audio>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pomodoroTimer', () => ({
                mode: 'pomodoro',
                timeLeft: 25 * 60,
                timer: null,
                pomodoroCount: 0,
                taskTimers: {},
                activeTaskId: null,
                isMuted: false,
                isAlarmPlaying: false,
                durations: {
                    pomodoro: 25 * 60,
                    shortBreak: 5 * 60,
                    longBreak: 15 * 60
                },

                playSound(soundId) {
                    if (this.isMuted) return;
                    
                    try {
                        const sound = document.getElementById(soundId);
                        if (sound) {
                            sound.pause();
                            sound.currentTime = 0;
                            // Lower volume for start sound
                            sound.volume = soundId === 'startSound' ? 0.3 : 0.5;
                            
                            const playPromise = sound.play();
                            if (playPromise !== undefined) {
                                // Only set isAlarmPlaying for completion sounds
                                if (soundId !== 'startSound') {
                                    this.isAlarmPlaying = true;
                                }
                                playPromise.catch(error => {
                                    console.error('Error playing sound:', error);
                                    this.isAlarmPlaying = false;
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error in playSound:', error);
                        this.isAlarmPlaying = false;
                    }
                },

                stopAlarm() {
                    ['alarmSound', 'taskCompleteSound', 'breakCompleteSound', 'startSound'].forEach(soundId => {
                        const sound = document.getElementById(soundId);
                        if (sound) {
                            sound.pause();
                            sound.currentTime = 0;
                        }
                    });
                    this.isAlarmPlaying = false;
                },

                toggleMute() {
                    this.isMuted = !this.isMuted;
                    if (this.isMuted) {
                        this.stopAlarm();
                    }
                    localStorage.setItem('pomodoroMuted', this.isMuted);
                },

                initializeTimers() {
                    // Load mute state
                    this.isMuted = localStorage.getItem('pomodoroMuted') === 'true';

                    // Load main timer state
                    const savedState = JSON.parse(localStorage.getItem('pomodoroState') || '{}');
                    this.mode = savedState.mode || 'pomodoro';
                    
                    // Load saved durations or use defaults
                    const savedDurations = JSON.parse(localStorage.getItem('pomodoroDurations') || '{}');
                    this.durations = {
                        pomodoro: (savedDurations.pomodoro || 25) * 60,
                        shortBreak: (savedDurations.shortBreak || 5) * 60,
                        longBreak: (savedDurations.longBreak || 15) * 60
                    };
                    
                    this.timeLeft = savedState.timeLeft || this.durations[this.mode];
                    this.pomodoroCount = savedState.pomodoroCount || 0;
                    this.activeTaskId = savedState.activeTaskId || null;

                    // Load task timers
                    const savedTaskTimers = JSON.parse(localStorage.getItem('taskTimers') || '{}');
                    @foreach($activeTasks as $task)
                        this.taskTimers[{{ $task->id }}] = savedTaskTimers[{{ $task->id }}] || {{ ($task->duration ?? 25) * 60 }};
                        // Set the first task as active if no active task
                        if (!this.activeTaskId) {
                            this.activeTaskId = {{ $task->id }};
                        }
                    @endforeach

                    // Initialize audio context on user interaction
                    document.addEventListener('click', () => {
                        const AudioContext = window.AudioContext || window.webkitAudioContext;
                        if (AudioContext && !window.audioContext) {
                            window.audioContext = new AudioContext();
                        }
                    }, { once: true });

                    // Save state periodically
                    setInterval(() => this.saveState(), 1000);

                    // Handle page visibility change
                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this.saveState();
                            if (this.timer) {
                                clearInterval(this.timer);
                                this.timer = null;
                            }
                        }
                    });

                    // Update tomato bite display
                    this.updateDisplay();
                },

                saveState() {
                    localStorage.setItem('pomodoroState', JSON.stringify({
                        mode: this.mode,
                        timeLeft: this.timeLeft,
                        pomodoroCount: this.pomodoroCount,
                        activeTaskId: this.activeTaskId
                    }));
                    localStorage.setItem('taskTimers', JSON.stringify(this.taskTimers));
                    
                    // Save all durations
                    localStorage.setItem('pomodoroDurations', JSON.stringify({
                        pomodoro: Math.floor(this.durations.pomodoro / 60),
                        shortBreak: Math.floor(this.durations.shortBreak / 60),
                        longBreak: Math.floor(this.durations.longBreak / 60)
                    }));
                },

                updateDisplay() {
                    let percentage = ((this.durations[this.mode] - this.timeLeft) / this.durations[this.mode]);
                    document.getElementById('tomatoBite').style.transform = `scaleY(${percentage})`;
                },

                startTimer() {
                    if (!this.timer) {
                        // Play the start sound
                        this.playSound('startSound');
                        
                        this.timer = setInterval(() => {
                            if (this.timeLeft > 0) {
                                this.timeLeft--;
                                this.updateDisplay();
                                
                                // Update task timers only during pomodoro mode
                                if (this.mode === 'pomodoro' && this.activeTaskId) {
                                    if (this.taskTimers[this.activeTaskId] > 0) {
                                        this.taskTimers[this.activeTaskId]--;
                                        
                                        // Check if current task timer is complete
                                        if (this.taskTimers[this.activeTaskId] === 0) {
                                            // Play task complete sound
                                            this.playSound('taskCompleteSound');
                                            
                                            // Find next uncompleted task
                                            const taskIds = Object.keys(this.taskTimers);
                                            const currentIndex = taskIds.indexOf(this.activeTaskId.toString());
                                            let nextTaskId = null;
                                            
                                            // Look for the next task with remaining time
                                            for (let i = currentIndex + 1; i < taskIds.length; i++) {
                                                if (this.taskTimers[taskIds[i]] > 0) {
                                                    nextTaskId = taskIds[i];
                                                    break;
                                                }
                                            }
                                            
                                            // If found next task, switch to it
                                            if (nextTaskId) {
                                                this.activeTaskId = parseInt(nextTaskId);
                                                this.saveState();
                                            }
                                        }
                                    }
                                }
                            } else {
                                this.handleTimerComplete();
                            }
                        }, 1000);
                    }
                },

                pauseTimer() {
                    if (this.timer) {
                        clearInterval(this.timer);
                        this.timer = null;
                    }
                },

                resetTimer() {
                    this.pauseTimer();
                    this.timeLeft = this.durations[this.mode];
                    this.updateDisplay();
                },

                handleTimerComplete() {
                    clearInterval(this.timer);
                    this.timer = null;
                    
                    if (this.mode === 'pomodoro') {
                        // Play task complete sound for Pomodoro completion
                        this.playSound('taskCompleteSound');
                        
                        this.pomodoroCount = (this.pomodoroCount + 1) % 8;
                        if (this.pomodoroCount % 4 === 0) {
                            this.switchMode('longBreak');
                        } else {
                            this.switchMode('shortBreak');
                        }
                    } else {
                        // Play break complete sound
                        this.playSound('breakCompleteSound');
                        this.switchMode('pomodoro');
                    }
                },

                switchMode(newMode) {
                    this.pauseTimer();
                    this.mode = newMode;
                    this.timeLeft = this.durations[newMode];
                    this.updateDisplay();
                },

                formatTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
                },

                async markTaskAsCompleted(taskId) {
                    try {
                        const response = await fetch(`/tasks/${taskId}/complete`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        if (response.ok) {
                            delete this.taskTimers[taskId];
                            this.saveState();
                            window.location.reload();
                        }
                    } catch (error) {
                        console.error('Error marking task as completed:', error);
                    }
                },

                async updateTaskDuration(taskId, duration) {
                    try {
                        const response = await fetch(`/tasks/${taskId}/duration`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ duration: parseInt(duration) })
                        });
                        if (response.ok) {
                            const task = await response.json();
                            this.taskTimers[taskId] = task.duration * 60;
                            this.saveState();
                        }
                    } catch (error) {
                        console.error('Error updating task duration:', error);
                    }
                },

                resetTaskTimer(taskId) {
                    // Find the input element for this specific task
                    const durationInput = document.querySelector(`input[type="number"][value="${Math.floor(this.taskTimers[taskId] / 60)}"]`);
                    if (durationInput) {
                        const duration = parseInt(durationInput.value);
                        this.taskTimers[taskId] = duration * 60;
                        this.saveState();
                    } else {
                        // Fallback to the original duration if input not found
                        @foreach($activeTasks as $task)
                            if ({{ $task->id }} === taskId) {
                                this.taskTimers[taskId] = {{ ($task->duration ?? 25) * 60 }};
                            }
                        @endforeach
                        this.saveState();
                    }
                },

                updateDuration(type, minutes) {
                    const duration = parseInt(minutes);
                    if (!isNaN(duration) && duration > 0) {
                        this.durations[type] = duration * 60;
                        // If currently in this mode, update timeLeft
                        if (this.mode === type) {
                            this.timeLeft = this.durations[type];
                            this.updateDisplay();
                        }
                        this.saveState();
                    }
                }
            }));
        });
    </script>
</body>
</html>
