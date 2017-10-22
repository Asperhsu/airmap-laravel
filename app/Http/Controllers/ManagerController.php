<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Thingspeak;
use App\Http\Requests\ChangeManagerPassword;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('manager.users');
    }

    public function users(Request $request)
    {
        $users = User::all();
        
        return view('manager.users', compact('users'));
    }

    public function showChangePassword(Request $request)
    {
        return view('manager.change-password');
    }

    public function storeChangePassword(ChangeManagerPassword $request)
    {
        if (Hash::check($request->input('old_password'), Auth::user()->password)) {
            $user = Auth::user();
            $user->password = bcrypt($request->input('password'));
            $user->save();

            return redirect()->route('manager.users');
        }

        return back()->withInput()->withErrors(['old_password' => '舊密碼不相同']);
    }

    public function probecube(Request $request)
    {
        $items = Thingspeak::probecube()->get();
        
        return view('manager.thingspeak', compact('items'));
    }

    public function independent(Request $request)
    {
        $items = Thingspeak::independent()->get();
        dd($items);
    }
}
