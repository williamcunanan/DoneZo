<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Task Manager - Cute Edition</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>
    body {
      font-family: 'Fredoka', sans-serif;
    }
    .background-cute {
      background: linear-gradient(to bottom right, #ffe4e1, #fff0f5);
    }
  </style>
</head>
<body class="background-cute p-10" style="background-image: url('https://i.pinimg.com/736x/be/f8/cd/bef8cdb1460f0288f26bf351a953751d.jpg');">
  <div x-data="taskManager()" class="max-w-xl mx-auto">
    <div class="flex items-center mb-6">
      <a href="{{ route('dashboard') }}" class="mr-4 text-[#E50046] hover:text-[#C4003D]">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
      </a>
      <h1 class="p-4 rounded-2xl bg-gradient-to-r from-yellow-50 to-yellow-200 shadow-lg border text-3xl font-bold text-[#E50046]">Task Manager</h1>
    </div>
    
    <!-- Error Alert -->
    <div x-show="errorMessage" class="mb-4 bg-pink-100 border border-pink-400 text-pink-700 px-4 py-3 rounded relative" role="alert">
      <span class="block sm:inline" x-text="errorMessage"></span>
      <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="errorMessage = ''">
        <svg class="fill-current h-6 w-6 text-pink-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <title>Close</title>
          <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
        </svg>
      </span>
    </div>
    
    <form @submit.prevent="addTask" class="bg-white/90 backdrop-blur-sm p-6 rounded-xl shadow-lg">
      <label class="block text-lg font-semibold mb-1">Task Name:</label>
      <input type="text" x-model="taskName" required class="w-full p-2 border rounded-full mb-3 focus:ring-2 focus:ring-pink-300">
      
      <label class="block text-lg font-semibold mb-1">Description (Optional):</label>
      <textarea x-model="taskDescription" class="w-full p-2 border rounded-lg mb-3 h-24 focus:ring-2 focus:ring-pink-300"></textarea>
      
      <label class="block text-lg font-semibold mb-1">Category:</label>
      <input type="text" x-model="taskCategory" class="w-full p-2 border rounded-full mb-3 focus:ring-2 focus:ring-pink-300">
      
      <div class="flex gap-4 mb-3">
        <div class="flex-1">
          <label class="block text-lg font-semibold mb-1">Start Date:</label>
          <input type="date" x-model="startDate" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-pink-300">
        </div>
        <div class="flex-1">
          <label class="block text-lg font-semibold mb-1">End Date:</label>
          <input type="date" x-model="endDate" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-pink-300">
        </div>
      </div>
      
      <button type="submit" class="w-full bg-[#E50046] text-white p-2 rounded-full transition-transform transform hover:scale-105 active:scale-95 shadow-lg">
        Add Task
      </button>
    </form>
    
    <ul class="mt-6 space-y-4">
      <template x-for="task in tasks" :key="task.id">
        <li class="p-4 rounded-2xl bg-gradient-to-r from-yellow-50 to-yellow-200 text-brown shadow-lg border transform transition hover:scale-105">
          <p class="text-xl font-bold" x-text="task.name"></p>
          <p class="text-base" x-text="task.description"></p>
          <p class="text-base">Category: <span x-text="task.category || '-'"></span></p>
          <p class="text-base">
            <span x-text="task.start_date ? formatDate(task.start_date) : '-'"></span>
            -
            <span x-text="task.end_date ? formatDate(task.end_date) : '-'"></span>
          </p>
          <div class="flex items-center space-x-2 mt-2">
            <span class="font-bold px-2 py-1 rounded-full text-sm"
                  x-bind:class="{'bg-green-200 text-green-800': task.completed, 'bg-yellow-200 text-yellow-800': !task.completed}">
              <span x-text="task.completed ? 'Done 🎉' : 'Pending'"></span>
            </span>
          </div>
          <div class="flex gap-2 mt-3">
            <button @click="markAsDone(task.id)" class="bg-green-400 hover:bg-green-500 text-white px-4 py-1 rounded-full transition shadow-md">
              ✓
            </button>
            <button @click="deleteTask(task.id)" class="bg-red-400 hover:bg-red-500 text-white px-4 py-1 rounded-full transition shadow-md">
              ✗
            </button>
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
        errorMessage: '',

        formatDate(date) {
          if (!date) return '-';
          return new Date(date).toLocaleDateString();
        },

        addTask() {
          if (!this.taskName.trim()) {
            this.errorMessage = 'Task name is required';
            return;
          }

          let newTask = {
            name: this.taskName.trim(),
            description: this.taskDescription.trim() || null,
            category: this.taskCategory.trim() || null,
            start_date: this.startDate || null,
            end_date: this.endDate || null,
            completed: false
          };

          fetch("/tasks", {
            method: "POST",
            headers: { 
              "Content-Type": "application/json", 
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content 
            },
            body: JSON.stringify(newTask)
          })
          .then(response => response.json())
          .then(data => {
            if (data.error) {
              this.errorMessage = typeof data.error === 'string' ? data.error : Object.values(data.error).flat().join('\n');
              return;
            }
            this.tasks.push(data);
            this.taskName = '';
            this.taskDescription = '';
            this.taskCategory = '';
            this.startDate = '';
            this.endDate = '';
            this.errorMessage = '';
          })
          .catch(error => {
            console.error("Error adding task:", error);
            this.errorMessage = "Failed to add task. Please try again.";
          });
        },

        deleteTask(id) {
          fetch(`/tasks/${id}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
          }).then(() => {
            this.tasks = this.tasks.filter(task => task.id !== id);
          });
        },

        markAsDone(id) {
          fetch(`/tasks/${id}`, {
            method: "PUT",
            headers: { 
              "Content-Type": "application/json", 
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content 
            },
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
