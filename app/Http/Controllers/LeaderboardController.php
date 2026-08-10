<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\RentalStatus;
use App\Domain\Loyalty\BaksoRank;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    private function getTopMembers(int $limit = 10)
    {
        return User::where('role', 'user')
            ->with('profile')
            ->withSum(['rentals' => function ($query) {
                $query->where('status', RentalStatus::Returned);
            }], 'duration_days')
            ->orderByDesc('rentals_sum_duration_days')
            ->take($limit)
            ->get()
            ->map(function ($user, $index) {
                $totalDays = (int) $user->rentals_sum_duration_days;
                $user->total_days = $totalDays;
                $user->rank = BaksoRank::fromDays($totalDays);
                $user->leaderboard_position = $index + 1;
                return $user;
            });
    }

    public function index()
    {
        $topMembers = $this->getTopMembers(10);
        
        return view('portal.leaderboard', compact('topMembers'));
    }

    public function adminIndex()
    {
        // Admin might want to see more members, e.g. top 50
        $topMembers = $this->getTopMembers(50);
        
        return view('admin.leaderboard', compact('topMembers'));
    }
}
