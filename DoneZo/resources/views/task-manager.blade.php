<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-[#FFF0BD] p-10">
    <div x-data="taskManager()" class="max-w-xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('dashboard') }}" class="mr-4 text-[#E50046] hover:text-[#C4003D]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-[#E50046]">Task Manager</h1>
        </div>
        <form @submit.prevent="addTask" class="bg-white p-6 rounded-lg shadow-md">
            <label class="block text-lg font-semibold">Task Name:</label>
            <input type="text" x-model="taskName" class="w-full p-2 border rounded mb-3">
            
            <label class="block text-lg font-semibold">Description (Optional):</label>
            <input type="text" x-model="taskDescription" class="w-full p-2 border rounded mb-3">
            
            <label class="block text-lg font-semibold">Category:</label>
            <input type="text" x-model="taskCategory" class="w-full p-2 border rounded mb-3">
            
            <label class="block text-lg font-semibold">Start Date:</label>
            <input type="date" x-model="startDate" class="w-full p-2 border rounded mb-3">
            
            <label class="block text-lg font-semibold">End Date:</label>
            <input type="date" x-model="endDate" class="w-full p-2 border rounded mb-3">
            
            <button type="submit" class="w-full bg-[#E50046] text-white p-2 rounded transition-transform transform hover:scale-105 active:scale-95">Add Task</button>
        </form>
        
        <ul class="mt-6 space-y-4">
            <template x-for="task in tasks" :key="task.id">
                <li class="p-4 border rounded bg-[#FDAB9E] text-white">
                    <p class="text-lg font-semibold" x-text="task.name"></p>
                    <p class="text-sm" x-text="task.description"></p>
                    <p class="text-sm">Category: <span x-text="task.category"></span></p>
                    <p class="text-sm"><span x-text="task.start_date"></span> - <span x-text="task.end_date"></span></p>
                    <p class="text-sm font-bold">Status: <span x-text="task.completed ? 'Done' : 'Pending'"></span></p>
                    <div class="flex gap-2 mt-2">
                        <button @click="markAsDone(task.id)" class="bg-green-500 px-3 py-1 rounded hover:bg-green-600">✓</button>
                        <button @click="deleteTask(task.id)" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">✗</button>
                    </div>
                </li>
            </template>
        </ul>
    </div>

    <script>
        function taskManager() {
            return {
                tasks: @json($tasks),
                taskName: '',
                taskDescription: '',
                taskCategory: '',
                startDate: '',
                endDate: '',
                
                addTask() {
                    let newTask = {
                        name: this.taskName,
                        description: this.taskDescription,
                        category: this.taskCategory,
                        start_date: this.startDate,
                        end_date: this.endDate,
                        completed: false
                    };
                    
                    fetch("/tasks", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: JSON.stringify(newTask)
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.tasks.push(data);
                        this.taskName = '';
                        this.taskDescription = '';
                        this.taskCategory = '';
                        this.startDate = '';
                        this.endDate = '';
                    })
                    .catch(error => console.error("Error adding task:", error));
                },
                
                deleteTask(id) {
                    fetch(`/tasks/${id}`, {
                        method: "DELETE",
                        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    }).then(() => {
                        this.tasks = this.tasks.filter(task => task.id !== id);
                    });
                },
                
                markAsDone(id) {
                    fetch(`/tasks/${id}`, {
                        method: "PUT",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: JSON.stringify({ completed: true })
                    }).then(() => {
                        let task = this.tasks.find(t => t.id === id);
                        if (task) task.completed = true;
                    });
                }
            };
        }
    </script>
</body>
</html>
