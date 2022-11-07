<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class LoginController extends Controller
{

    public function index()
    {
        return view("admin.auth.login");
    }
    public function loginAttemp(Request $request)
    {

        $this->validator($request);
        if (Auth::guard('admin')->attempt($request->only("phone", 'password'), $request->filled('remember'))) {
            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('status', __('admin.login_success'));
        }

        //Authentication failed...
        return $this->loginFailed();
    }
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login')->with('status', __('admin.logout_message'));
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }
    private function loginFailed()
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', __('admin.login_failed'));
    }
    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'phone' => 'required',
            'password' => 'required',
        ];
        //validate the request.
        $request->validate($rules);
    }
}
