<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Helpers\AuthHelper;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = AuthHelper::id();
        $notes = Note::where('user_id', $userId)
                    ->withCount('pages')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('auth.dashboard', compact('notes'));
    }
}