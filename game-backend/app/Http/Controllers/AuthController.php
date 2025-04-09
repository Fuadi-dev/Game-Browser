<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Loglogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    function loginPost(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $email = User::where('email', $request->email)->first();
        if(!$email){
            return redirect()->back()->with('error', 'Email not found');
        }
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Mencatat login
            $log = new Loglogin();
            $log->user_id = Auth::id();
            $log->ip_address = $request->ip();
            $log->browser = $request->header('User-Agent');
            $log->timezone = config('app.timezone');
            $log->login_at = now();
            $log->save();

            // Simpan ID log di session
            session(['current_login_id' => $log->id]);

            // Authentication passed...
            return redirect('/')->with('success', 'Login successful');
        } else {
            return redirect()->with('error', 'Password is incorrect');
        }
    }

    public function logout(Request $request)
    {
        // Mencatat logout
        if (session()->has('current_login_id')) {
            $logId = session('current_login_id');
            $log = Loglogin::find($logId);
            if ($log) {
                $log->logout_at = now();
                $log->save();
            }
        }

        Auth::logout();
        return redirect('/login')->with('success', 'Logout successful');
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        // return redirect()->away('http://localhost:5173/#login');
    }

    // Menampilkan riwayat login
    public function loginHistory(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default 10 item per halaman
        $search = $request->get('search', ''); // Parameter pencarian
        $sortBy = $request->get('sort_by', 'login_at'); // Default sort by login time
        $sortDir = $request->get('sort_dir', 'desc'); // Default newest first

        // Start with eager loading users to access their fields
        $query = Loglogin::with('user');

        // Fitur pencarian (mencari melalui relasi user)
        if (!empty($search)) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('ip_address', 'like', "%{$search}%");
        }

        // Pengurutan data - handle columns from related tables
        if (in_array($sortBy, ['name', 'email'])) {
            // Sort by user fields requires a join
            $query->join('users', 'loglogins.user_id', '=', 'users.id')
                  ->select('loglogins.*')
                  ->orderBy("users.{$sortBy}", $sortDir);
        } else {
            // Sort by loglogins table fields
            $query->orderBy($sortBy, $sortDir);
        }

        // Pagination dengan parameter dinamis
        $logs = $query->paginate($perPage)->withQueryString();

        return view('Log.log-login', compact('logs', 'search', 'perPage', 'sortBy', 'sortDir'));
    }
}
