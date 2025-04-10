<x-app-layout>
    @csrf
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .bg-cute {
            background-image: url('/images/uwp1772743.webp');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card-yellow {
            background: rgba(255, 248, 214, 0.95);
            border: 2px solid rgba(255, 180, 166, 0.2);
            padding: 1.75rem;
        }
        .card-pink {
            background: rgba(255, 192, 183, 0.95);
            border: 2px solid rgba(229, 0, 70, 0.2);
            padding: 1.75rem;
        }
        .tool-card {
            height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .section-title {
            font-size: 1.75rem;
            color: #2B4D12;
            font-family: 'Fredoka', sans-serif;
            font-weight: bold;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .task-overview {
            margin-top: 2.5rem;
        }
    </style>

    <!-- Define taskManager function in the head section -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('taskManager', () => ({
                task: {
                    id: null,
                    name: '',
                    description: '',
                    category: '',
                    start_date: '',
                    end_date: '',
                    completed: false
                },
                showEditModal: false,
                showDeleteModal: false,
                showCreateModal: false,
                taskToDelete: null,

                completeTask(taskId) {
                    const token = document.querySelector('meta[name=csrf-token]').content;
                    console.log('Completing task:', taskId);
                    
                    fetch(`/tasks/${taskId}/complete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`Network response was not ok: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            // Move the task to completed section without page refresh
                            const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);
                            if (taskElement) {
                                const completedTasksContainer = document.querySelector('#completed-tasks');
                                if (completedTasksContainer) {
                                    completedTasksContainer.appendChild(taskElement);
                                }
                            }
                        } else {
                            throw new Error('Failed to complete task');
                        }
                    })
                    .catch(error => {
                        console.error('Error completing task:', error);
                        alert('Failed to complete task. Please try again.');
                    });
                },

                activateTask(taskId) {
                    const token = document.querySelector('meta[name=csrf-token]').content;
                    console.log('Activating task:', taskId);
                    
                    fetch(`/tasks/${taskId}/activate`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`Network response was not ok: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            window.location.reload();
                        } else {
                            throw new Error('Failed to activate task');
                        }
                    })
                    .catch(error => {
                        console.error('Error activating task:', error);
                        alert('Failed to activate task. Please try again.');
                    });
                },

                deleteTask(taskId) {
                    this.taskToDelete = taskId;
                    this.showDeleteModal = true;
                },

                editTask(taskId) {
                    const token = document.querySelector('meta[name=csrf-token]').content;
                    
                    fetch(`/tasks/${taskId}/edit`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.task = data;
                        this.showEditModal = true;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to load task details. Please try again.');
                    });
                },

                confirmDelete() {
                    const token = document.querySelector('meta[name=csrf-token]').content;
                    
                    fetch(`/tasks/${this.taskToDelete}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            throw new Error('Operation failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to delete task. Please try again.');
                    });
                    
                    this.showDeleteModal = false;
                },

                updateTask() {
                    const token = document.querySelector('meta[name=csrf-token]').content;
                    
                    fetch(`/tasks/${this.task.id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.task)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.showEditModal = false;
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to update task. Please try again.');
                    });
                },
                
                createTask() {
                    const token = document.querySelector('meta[name=csrf-token]').content;
                    
                    fetch('/tasks', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.task)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.showCreateModal = false;
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to create task. Please try again.');
                    });
                }
            }));
        });
    </script>

    <div class="min-h-screen bg-cute">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-16">
            <!-- Welcome Section -->
            <div class="card mb-10 text-center">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Tomato" class="w-6 h-6">
                    <h1 class="text-3xl text-[#2B4D12] font-['Fredoka'] font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
                </div>
                <p class="text-gray-600 font-['Fredoka']">Here's a quick overview of your tasks and productivity tools.</p>
            </div>

            <!-- Tools Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-12">
                <!-- To-Do List Card -->
                <a href="{{ route('tasks.index') }}" class="card card-yellow tool-card group">
                    <div>
                        <h2 class="text-2xl text-[#2B4D12] font-['Fredoka'] font-bold mb-2">To-Do List</h2>
                        <p class="text-gray-600">Manage your daily tasks and boost productivity.</p>
                    </div>
                    <div class="flex justify-between items-end pt-2">
                        <span class="text-2xl">📝</span>
                        <span class="inline-flex items-center text-[#E50046] font-['Fredoka'] font-semibold group-hover:translate-x-2 transition-transform duration-300">
                            Go to Tasks
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                    </div>
                </a>

                <!-- Pomodoro Timer Card -->
                <a href="{{ route('pomodoro.index') }}" class="card card-pink tool-card group">
                    <div>
                        <h2 class="text-2xl text-[#2B4D12] font-['Fredoka'] font-bold mb-2">Pomodoro Timer</h2>
                        <p class="text-gray-600">Stay focused with structured work and break intervals.</p>
                    </div>
                    <div class="flex justify-between items-end pt-2">
                        <span class="text-2xl">⏰</span>
                        <span class="inline-flex items-center text-[#E50046] font-['Fredoka'] font-semibold group-hover:translate-x-2 transition-transform duration-300">
                            Start Focus
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                    </div>
                </a>
            </div>

            <!-- Task Overview Title -->
            <div class="flex items-center gap-2 mb-6 px-1">
                <svg class="w-5 h-5 text-[#2B4D12]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h2 class="text-xl text-[#2B4D12] font-['Fredoka'] font-bold">Task Overview</h2>
            </div>

            <!-- Task Lists -->
            <div class="space-y-6" x-data="taskManager">
                <!-- Create Task Button -->
                <div class="flex justify-end mb-4">
                    <button @click="showCreateModal = true" class="inline-flex items-center px-4 py-2 bg-[#E50046] text-white font-semibold rounded-lg shadow-sm hover:bg-[#C4003D] transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Task
                    </button>
                </div>

                <!-- Create Task Modal -->
                <div x-show="showCreateModal" 
                    class="fixed inset-0 z-50 overflow-y-auto"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form @submit.prevent="createTask">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="mb-4">
                                        <label for="new-task-name" class="block text-gray-700 text-sm font-bold mb-2">Task Name</label>
                                        <input type="text" id="new-task-name" name="task_name" x-model="task.name" required
                                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E50046]">
                                    </div>
                                    <div class="mb-4">
                                        <label for="new-task-description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                        <textarea id="new-task-description" name="task_description" x-model="task.description" rows="3"
                                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E50046]"></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="new-task-category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                                        <input type="text" id="new-task-category" name="task_category" x-model="task.category"
                                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E50046]">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="mb-4">
                                            <label for="new-task-start-date" class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                                            <input type="date" id="new-task-start-date" name="task_start_date" x-model="task.start_date"
                                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E50046]">
                                        </div>
                                        <div class="mb-4">
                                            <label for="new-task-end-date" class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                                            <input type="date" id="new-task-end-date" name="task_end_date" x-model="task.end_date"
                                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E50046]">
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#E50046] text-base font-medium text-white hover:bg-[#C4003D] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E50046] sm:ml-3 sm:w-auto sm:text-sm">
                                        Create Task
                                    </button>
                                    <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E50046] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Active Tasks -->
                <div class="card">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-5 h-5 text-[#2B4D12]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h2 class="text-xl text-[#2B4D12] font-['Fredoka'] font-bold">Active Tasks</h2>
                    </div>
                    <div class="space-y-3">
                        @forelse($activeTasks as $task)
                            <div class="group flex items-center justify-between p-4 bg-[#FFF0BD]/50 rounded-xl transition-all duration-300 hover:shadow-md" data-task-id="{{ $task->id }}">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <span class="text-gray-700 font-medium">{{ $task->name }}</span>
                                    </div>
                                    @if($task->description)
                                        <p class="mt-1 text-sm text-gray-600">{{ $task->description }}</p>
                                    @endif
                                    @if($task->due_date)
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Due {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1 ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button x-on:click="completeTask({{ $task->id }})" 
                                        class="p-2 text-gray-500 hover:text-green-500 hover:bg-white/50 rounded-lg transition-colors"
                                        title="Mark as completed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                    <button x-on:click="editTask({{ $task->id }})" 
                                        class="p-2 text-gray-500 hover:text-[#E50046] hover:bg-white/50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button x-on:click="deleteTask({{ $task->id }})" 
                                        class="p-2 text-gray-500 hover:text-[#E50046] hover:bg-white/50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-gray-500 font-['Fredoka']">No active tasks</p>
                                <p class="text-gray-400 font-['Fredoka'] mt-1">Ready to be productive? Add your first task!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Edit Task Modal -->
                <div x-show="showEditModal" 
                    class="fixed inset-0 z-50 overflow-y-auto"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form @submit.prevent="updateTask">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="mb-4">
                                        <label for="task-name" class="block text-gray-700 text-sm font-bold mb-2">Task Name</label>
                                        <input type="text" id="task-name" name="task_name" x-model="task.name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E50046]">
                                    </div>
                                    <div class="mb-4">
                                        <label for="task-description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                        <textarea id="task-description" name="task_description" x-model="task.description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E50046]"></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="task-category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                                        <input type="text" id="task-category" name="task_category" x-model="task.category" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E50046]">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="mb-4">
                                            <label for="task-start-date" class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                                            <input type="date" id="task-start-date" name="task_start_date" x-model="task.start_date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E50046]">
                                        </div>
                                        <div class="mb-4">
                                            <label for="task-end-date" class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                                            <input type="date" id="task-end-date" name="task_end_date" x-model="task.end_date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E50046]">
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#E50046] text-base font-medium text-white hover:bg-[#C4003D] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E50046] sm:ml-3 sm:w-auto sm:text-sm">
                                        Save Changes
                                    </button>
                                    <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E50046] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Completed Tasks -->
                <div class="card">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="text-xl">🎉</span>
                        <h2 class="text-xl text-[#2B4D12] font-['Fredoka'] font-bold">Completed Tasks</h2>
                    </div>
                    <div class="space-y-3" id="completed-tasks">
                        @forelse($completedTasks as $task)
                            <div class="group flex items-center justify-between p-4 bg-gray-50 rounded-xl transition-all duration-300 hover:shadow-md" data-task-id="{{ $task->id }}">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <button x-on:click="activateTask({{ $task->id }})" 
                                            class="p-2 text-gray-500 hover:text-green-500 hover:bg-white/50 rounded-lg transition-colors"
                                            title="Mark as active">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <span class="ml-3 text-gray-500 line-through font-medium">{{ $task->name }}</span>
                                    </div>
                                    @if($task->description)
                                        <p class="ml-8 mt-1 text-sm text-gray-400 line-through">{{ $task->description }}</p>
                                    @endif
                                    @if($task->updated_at)
                                        <div class="ml-8 mt-2 flex items-center text-sm text-gray-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Completed {{ \Carbon\Carbon::parse($task->updated_at)->format('M d, Y') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1 ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button x-on:click="deleteTask({{ $task->id }})" 
                                        class="p-2 text-gray-400 hover:text-[#E50046] hover:bg-white rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-gray-500 font-['Fredoka']">No completed tasks yet</p>
                                <p class="text-gray-400 font-['Fredoka'] mt-1">Complete some tasks to see them here</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ showDeleteModal: false, taskId: null }" class="relative z-50">
        <div
            x-show="showDeleteModal"
            class="fixed inset-0 transform transition-all"
            x-on:click="showDeleteModal = false"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div
            x-show="showDeleteModal"
            class="mb-6 bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-[#E50046]">Delete Task</h3>
                <p class="mt-2 text-gray-600">Are you sure you want to delete this task? This action cannot be undone.</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                <button @click="showDeleteModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E50046] transition-colors">
                    Cancel
                </button>
                <button @click="confirmDelete()" class="px-4 py-2 text-sm font-medium text-white bg-[#E50046] border border-transparent rounded-lg hover:bg-[#C4003D] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E50046] transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>
</x-app-layout>

