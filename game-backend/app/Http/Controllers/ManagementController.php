<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManagementController extends Controller
{

    public function users(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default 10 item per halaman
        $search = $request->get('search', ''); // Parameter pencarian
        $sortBy = $request->get('sort_by', 'name'); // Sortir berdasarkan kolom
        $sortDir = $request->get('sort_dir', 'asc'); // Arah pengurutan

        $query = User::query();

        // Fitur pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        // Pengurutan data
        $query->orderBy($sortBy, $sortDir);

        // Pagination dengan parameter dinamis
        $users = $query->paginate($perPage)->withQueryString();

        return view('users.users', compact('users', 'search', 'sortBy', 'sortDir', 'perPage'));
    }

    public function category(Request $request){
        $perPage = $request->get('per_page', 10); // Default 10 item per halaman
        $search = $request->get('search', ''); // Parameter pencarian
        $sortBy = $request->get('sort_by', 'name'); // Sortir berdasarkan kolom
        $sortDir = $request->get('sort_dir', 'asc'); // Arah pengurutan

        $query = Category::query();
        // Fitur pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Pengurutan data
        $query->orderBy($sortBy, $sortDir);

        // Pagination dengan parameter dinamis
        $category = $query->paginate($perPage)->withQueryString();

        return view('category.category', compact('category', 'search', 'sortBy', 'sortDir', 'perPage'));
    }

    //add function
    function addUserPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required',
            'role' => 'required',
        ]);

        $email = User::where('email', $request->email)->first();
        if ($email) {
            // Gunakan with untuk mengirim pesan error ke session
            return redirect()->back()->with('error', 'Email is already registered');
        }

        if ($request->password != $request->password_confirmation) {
            // Gunakan with untuk mengirim pesan error ke session
            return redirect()->back()->with('error', 'Password and confirmation do not match');
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            if ($user) {
                return redirect()->back()->with('success', 'User created successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to create user');
            }
        }
    }

    //add category function
    public function addCategory(Request $request){
        $request->validate([
            'name' => 'required',
        ]);
        $uniqueCategory = Category::where('name', $request->name)->first();
        if ($uniqueCategory) {
            return redirect()->back()->with('error', 'Category already exists');
        }
        $category = Category::create([
            'name' => $request->name,
        ]);
        if ($category) {
            return redirect()->back()->with('success', 'Category created successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to create category');
        }
    }

    //update function
    function updateRole(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            $user->role = $request->role;
            $user->save();
            return redirect()->back()->with('success', 'User role updated successfully');
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }
    function updateStatus(Request $request){
        $user = User::find($request->user_id);
        if ($user) {
            $user->status = $request->status;
            $user->save();
            return redirect()->back()->with('success', 'User status updated successfully');
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }

    //update category function
    public function updateCategory(Request $request){
        $request->validate([
            'name' => 'required|unique:categories,name,' . $request->category_id,
            'category_id' => 'required|exists:categories,id',
        ]);

        $category = Category::find($request->category_id);
        if ($category) {
            $category->name = $request->name;
            $category->save();
            return redirect('/category')->with('success', 'Category updated successfully');
        } else {
            return redirect('/category')->with('error', 'Category not found');
        }
    }

    //delete function
    public function userDelete(Request $request){
        $user = User::find($request->user_id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully');
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }

    //delete category function
    public function deleteCategory(Request $request){
        $category = Category::find($request->category_id);
        if ($category) {
            // Check if category is in use
            if ($category->games()->count() > 0) {
                return redirect('/category')->with('error', 'Cannot delete category because it is in use');
            }

            $category->delete();
            return redirect('/category')->with('success', 'Category deleted successfully');
        } else {
            return redirect('/category')->with('error', 'Category not found');
        }
    }

}
