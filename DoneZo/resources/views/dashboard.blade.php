<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[#E50046] leading-tight">
            {{ __('Welcome to DoneZo') }}
        </h2>
        <span class="text-sm text-gray-600">Manage your tasks efficiently</span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Navigation Buttons -->
            <div class="mb-12 flex justify-center gap-6">
                <a href="{{ route('pomodoro.index') }}" class="group inline-flex items-center px-8 py-4 bg-[#E50046] hover:bg-[#C4003D] text-white font-semibold rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                    <div class="p-3 bg-white/10 rounded-lg mr-4 group-hover:bg-white/20 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span>Pomodoro Timer</span>
                </a>
                <a href="{{ route('tasks.index') }}" class="group inline-flex items-center px-8 py-4 bg-[#FDAB9E] hover:bg-[#E99A8E] text-white font-semibold rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                    <div class="p-3 bg-white/10 rounded-lg mr-4 group-hover:bg-white/20 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <span>Tasks</span>
                </a>
            </div>

            <!-- Task Lists -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8" x-data="taskManager">
                <!-- Active Tasks -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-[#E50046]">Active Tasks</h3>
                            <span class="px-3 py-1 bg-[#FFF0BD] text-[#E50046] rounded-full text-sm font-medium">
                                {{ count($activeTasks) }} tasks
                            </span>
                        </div>
                        <div class="space-y-4">
                            @forelse($activeTasks as $task)
                                <div class="group flex items-center justify-between p-4 bg-[#FFF0BD] rounded-xl transition-all duration-300 hover:shadow-md">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                class="w-5 h-5 text-[#E50046] rounded border-gray-300 focus:ring-[#E50046] transition-colors"
                                                @change="markTaskAsCompleted({{ $task->id }})">
                                            <span class="ml-3 text-gray-700 font-medium group-hover:text-[#E50046] transition-colors">{{ $task->name }}</span>
                                        </div>
                                        @if($task->description)
                                            <p class="ml-8 mt-1 text-sm text-gray-600">{{ $task->description }}</p>
                                        @endif
                                        <div class="ml-8 mt-2 flex items-center gap-4 text-sm text-gray-500">
                                            @if($task->category)
                                                <span class="inline-flex items-center px-2 py-1 bg-white/50 rounded-full">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                    {{ $task->category }}
                                                </span>
                                            @endif
                                            @if($task->start_date)
                                                <span class="inline-flex items-center px-2 py-1 bg-white/50 rounded-full">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($task->start_date)->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3 ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="editTask({{ $task->id }})" class="p-2 text-[#E50046] hover:text-[#C4003D] hover:bg-white/50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button @click="deleteTask({{ $task->id }})" class="p-2 text-[#E50046] hover:text-[#C4003D] hover:bg-white/50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
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

                <!-- Completed Tasks -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-[#E50046]">Completed Tasks</h3>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                {{ count($completedTasks) }} completed
                            </span>
                        </div>
                        <div class="space-y-4">
                            @forelse($completedTasks as $task)
                                <div class="group flex items-center justify-between p-4 bg-gray-50 rounded-xl transition-all duration-300 hover:shadow-md hover:bg-gray-100">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <input type="checkbox" checked
                                                class="w-5 h-5 text-green-500 rounded border-gray-300 focus:ring-green-500 transition-colors"
                                                @change="markTaskAsActive({{ $task->id }})">
                                            <span class="ml-3 text-gray-500 line-through font-medium group-hover:text-gray-700 transition-colors">{{ $task->name }}</span>
                                        </div>
                                        @if($task->description)
                                            <p class="ml-8 mt-1 text-sm text-gray-400 line-through">{{ $task->description }}</p>
                                        @endif
                                        <div class="ml-8 mt-2 flex items-center gap-4 text-sm text-gray-400">
                                            @if($task->category)
                                                <span class="inline-flex items-center px-2 py-1 bg-white rounded-full border border-gray-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                    {{ $task->category }}
                                                </span>
                                            @endif
                                            @if($task->start_date)
                                                <span class="inline-flex items-center px-2 py-1 bg-white rounded-full border border-gray-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($task->start_date)->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3 ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="editTask({{ $task->id }})" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button @click="deleteTask({{ $task->id }})" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500">No completed tasks yet</p>
                                    <p class="mt-1 text-sm text-gray-400">Complete some tasks to see them here</p>
                                </div>
                            @endforelse
                        </div>
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

    <!-- Edit Task Modal -->
    <div x-data="{ showEditModal: false, task: null }" class="relative z-50">
        <div
            x-show="showEditModal"
            class="fixed inset-0 transform transition-all"
            x-on:click="showEditModal = false"
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
            x-show="showEditModal"
            class="mb-6 bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-[#E50046]">Edit Task</h3>
            </div>
            <form @submit.prevent="updateTask" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Task Name</label>
                    <input type="text" x-model="task.name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E50046] focus:ring-[#E50046] transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea x-model="task.description" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E50046] focus:ring-[#E50046] transition-colors"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" x-model="task.category" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E50046] focus:ring-[#E50046] transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" x-model="task.start_date" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E50046] focus:ring-[#E50046] transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" x-model="task.end_date" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E50046] focus:ring-[#E50046] transition-colors">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E50046] transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#E50046] border border-transparent rounded-lg hover:bg-[#C4003D] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E50046] transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function taskManager() {
            return {
                deleteTask(taskId) {
                    this.$data.showDeleteModal = true;
                    this.$data.taskId = taskId;
                },
                confirmDelete() {
                    fetch(`/tasks/${this.$data.taskId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                    this.$data.showDeleteModal = false;
                },
                editTask(taskId) {
                    fetch(`/tasks/${taskId}`)
                        .then(response => response.json())
                        .then(data => {
                            this.$data.task = data;
                            this.$data.showEditModal = true;
                        });
                },
                updateTask() {
                    fetch(`/tasks/${this.$data.task.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.$data.task)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                    this.$data.showEditModal = false;
                },
                markTaskAsCompleted(taskId) {
                    fetch(`/tasks/${taskId}/complete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                },
                markTaskAsActive(taskId) {
                    fetch(`/tasks/${taskId}/activate`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
