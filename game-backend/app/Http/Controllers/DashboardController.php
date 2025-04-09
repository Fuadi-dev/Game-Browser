<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Inisialisasi data dashboard
        $data = [];

        // Data umum yang dibutuhkan oleh semua role
        $data['totalCategories'] = Category::count();

        // Jika user adalah admin
        if (Auth::user()->role === 'admin') {
            // Total game dari semua developer
            $data['totalGames'] = Game::count();

            // Total developer
            $data['totalDevelopers'] = User::where('role', 'developer')->count();

            // Developer dengan game terbanyak
            $data['topDeveloper'] = User::select('users.name', DB::raw('COUNT(games.id) as game_count'))
                ->join('games', 'users.id', '=', 'games.user_id')
                ->where('users.role', 'developer')
                ->groupBy('users.id', 'users.name')
                ->orderBy('game_count', 'desc')
                ->first();

            // Game dengan jumlah played terbanyak
            $data['topGames'] = Game::select('id', 'name', 'image', 'played')
                ->orderBy('played', 'desc')
                ->take(3)
                ->get();

            // Games per kategori
            $data['gamesByCategory'] = Category::select('categories.name', DB::raw('COUNT(category_game.game_id) as game_count'))
                ->leftJoin('category_game', 'categories.id', '=', 'category_game.category_id')
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('game_count', 'desc')
                ->get();

        }
        // Jika user adalah developer
        else if (Auth::user()->role === 'developer') {
            // Total game milik developer ini
            $data['totalGames'] = Game::where('user_id', Auth::user()->id)->count();

            // Game dengan jumlah played terbanyak milik developer ini
            $data['topGames'] = Game::select('id', 'name', 'image', 'played')
                ->where('user_id', Auth::user()->id)
                ->orderBy('played', 'desc')
                ->take(3)
                ->get();

            // Kategori yang paling banyak digunakan oleh developer ini
            $data['topCategories'] = Category::select('categories.name', DB::raw('COUNT(category_game.game_id) as game_count'))
                ->leftJoin('category_game', 'categories.id', '=', 'category_game.category_id')
                ->leftJoin('games', 'category_game.game_id', '=', 'games.id')
                ->where('games.user_id', Auth::user()->id)
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('game_count', 'desc')
                ->take(5)
                ->get();
        }

        return view('dashboard', compact('data'));
    }
}
