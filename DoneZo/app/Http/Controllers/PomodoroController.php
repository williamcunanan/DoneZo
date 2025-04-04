<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pomodoro;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class PomodoroController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeTasks = Task::where('user_id', $user->id)
            ->where('completed', false)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pomodoro.index', compact('activeTasks'));
    }

    public function updateSession(Request $request)
    {
        $user = Auth::user();
        $pomodoro = Pomodoro::firstOrCreate(['user_id' => $user->id]);
        $pomodoro->session_count = ($pomodoro->session_count + 1) % 8;
        $pomodoro->save();

        return response()->json(['session_count' => $pomodoro->session_count]);
    }

    public function updateTaskDuration(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $task->duration = $request->duration;
        $task->save();
        
        return response()->json($task);
    }
}
