<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-3xl text-[#E50046] leading-tight">
                    {{ __('Create New Task') }}
                </h2>
                <p class="mt-2 text-gray-600">Add a new task to your list</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-sm hover:bg-gray-50 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
                <div class="p-6">
                    <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Task Name</label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E50046] focus:ring-[#E50046] transition-colors">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E50046] focus:ring-[#E50046] transition-colors"></textarea>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <input type="text" name="category" id="category"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E50046] focus:ring-[#E50046] transition-colors">
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E50046] focus:ring-[#E50046] transition-colors">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" name="end_date" id="end_date"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E50046] focus:ring-[#E50046] transition-colors">
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E50046] transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#E50046] border border-transparent rounded-lg hover:bg-[#C4003D] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#E50046] transition-colors">
                                Create Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 