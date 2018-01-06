<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Hash;
use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\Thingspeak;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeAdminPassword;

class AdminController extends Controller
{
    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function index(Request $request)
    {
        return redirect()->route('admin.users');
    }

    public function users(Request $request)
    {
        $userId = $this->guard()->id();
        $users = Admin::all();

        return view('admin.users', compact('userId', 'users'));
    }

    public function showChangePassword(Request $request, int $id)
    {
        $isSelf = $id === $this->guard()->id();
        $editUser = Admin::findOrFail($id);

        return view('admin.auth.change-password', compact('isSelf', 'editUser'));
    }

    public function storeChangePassword(ChangeAdminPassword $request, int $id)
    {
        $editUser = Admin::findOrFail($id);
        $currentUser = $this->guard()->user();

        if (Hash::check($request->input('old_password'), $currentUser->password)) {
            $editUser->password = bcrypt($request->input('password'));
            $editUser->save();

            return redirect()->route('admin.users')->with('alert-success', '更改密碼成功');
        }

        $isSelf = $id === $currentUser->id;
        $msg = $isSelf ? '舊密碼不相同' : '您的密碼與輸入不符';

        return back()->withInput()->withErrors(['old_password' => $msg]);
    }
}
