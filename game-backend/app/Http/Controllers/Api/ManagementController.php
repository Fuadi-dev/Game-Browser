<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagementController extends Controller
{
    public function users(Request $request){
        $per_page = $request->per_page ?? 10;
        $sorting = $request->sorting ?? 'asc';
        $orderby = $request->orderby ?? 'created_at';
        $user = User::orderBy($orderby, $sorting)->paginate($per_page);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function user($id){
        $user = User::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function userUpdate(Request $request, $id){
        $user = User::find($request->$id);
        $hash = $request->password ? Hash::make($request->password) : $user->password;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->password = $request->$hash;
        $user->save();
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function userDelete($id){
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }
}
