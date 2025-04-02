<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $activeTasks = Task::where('user_id', $user->id)
            ->where('completed', false)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $completedTasks = Task::where('user_id', $user->id)
            ->where('completed', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dashboard', compact('activeTasks', 'completedTasks'));
    }
} 