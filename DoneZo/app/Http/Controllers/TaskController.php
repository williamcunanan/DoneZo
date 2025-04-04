<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller {
    public function index() {
        $tasks = Task::where('user_id', Auth::id())->get();
        return view('task-manager', compact('tasks'));
    }

    public function create() {
        return view('tasks.create');
    }

    public function store(Request $request) {
        try {
            $data = $request->json()->all();
            
            // Validate the input
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:255',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Create the task
            $task = Task::create([
                'user_id' => Auth::id(),
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'category' => $data['category'] ?? null,
                'start_date' => !empty($data['start_date']) ? $data['start_date'] : null,
                'end_date' => !empty($data['end_date']) ? $data['end_date'] : null,
                'completed' => false,
                'duration' => $data['duration'] ?? 25
            ]);

            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, Task $task) {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $task->update($request->all());
        return response()->json($task);
    }

    public function destroy(Task $task) {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }

    public function complete(Task $task) {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $task->completed = true;
        $task->save();
        return response()->json($task);
    }

    public function updateDuration(Request $request, Task $task) {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $task->duration = $request->duration;
        $task->save();
        return response()->json($task);
    }
}