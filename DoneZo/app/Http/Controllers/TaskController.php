<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller {
    public function index() {
        $tasks = Task::all();
        return view('task-manager', compact('tasks'));
    }

    public function store(Request $request) {
        $task = Task::create($request->all());
        return response()->json($task);
    }

    public function update(Request $request, Task $task) {
        $task->update($request->all());
        return response()->json($task);
    }

    public function destroy(Task $task) {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}