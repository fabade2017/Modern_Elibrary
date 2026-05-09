<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        $topUsers = User::orderBy('points', 'desc')
                        ->take(5)
                        ->get();

        return view('student.leaderboard', compact('topUsers'));
    }
}
