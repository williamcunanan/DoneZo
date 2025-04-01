<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pomodoro;
use Illuminate\Support\Facades\Auth;

class PomodoroController extends Controller
{
    public function index()
    {
        return view('pomodoro.index');
    }

    public function updateSession(Request $request)
    {
        $user = Auth::user();
        $pomodoro = Pomodoro::firstOrCreate(['user_id' => $user->id]);
        $pomodoro->session_count = ($pomodoro->session_count + 1) % 8;
        $pomodoro->save();

        return response()->json(['session_count' => $pomodoro->session_count]);
    }
}
