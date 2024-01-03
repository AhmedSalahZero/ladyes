<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function getLoginForm(){
        if (Auth::guard('admin')->check())
            return redirect()->route('dashboard.index');
        else
            return view('admin.auth.login');
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|max:50',
            'password' => 'required|min:8|max:50',
        ]);
        $admin = Admin::where('email',$request->email)->first();
        if ($admin){
            if (!Hash::check( $request->password, $admin->password ))
                return redirect()->back()->with(['error' => __('msg.error_login')]);
            if ($admin->active == 1){
                    Auth::guard('admin')->login($admin);
                return redirect(route('dashboard.index'));
            }
            else{
                return redirect()->back()->with(['error' => __('msg.account_not_active')]);
            }
        }else{
            return redirect()->back()->with(['error' => __('msg.error_login')]);
        }
    }

    public function logout()
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect()->route('get.admin.login');
        }
        else
        {
            return redirect()->route('get.admin.login');
        }

    }
}
