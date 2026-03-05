<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\Coordinator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        $roles = [
            'student' => Student::class,
            'supervisor' => Supervisor::class,
            'coordinator' => Coordinator::class,
        ];

        foreach ($roles as $role => $model) {
            $user = $model::where('email', $email)->first();

            if ($user && Hash::check($password, $user->password_hash)) {
                Session::put('user_id', $user->id);
                Session::put('role', $role);
                Session::put('email', $user->email);
                Session::put('first_login', $user->first_login);

                if ($user->first_login) {
                    return redirect()->route('change-password');
                }

                return match($role) {
                    'student' => redirect()->route('student.dashboard'),
                    'supervisor' => redirect()->route('supervisor.dashboard'),
                    'coordinator' => redirect()->route('coordinator.dashboard'),
                };
            }
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    public function showChangePassword()
    {
        if (!Session::has('user_id') || !Session::get('first_login')) {
            return redirect()->route('login');
        }

        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        $userId = Session::get('user_id');
        $role = Session::get('role');

        $model = match($role) {
            'student' => Student::class,
            'supervisor' => Supervisor::class,
            'coordinator' => Coordinator::class,
            default => null,
        };

        if (!$model) {
            return redirect()->route('login');
        }

        $user = $model::find($userId);
        $user->password_hash = Hash::make($request->new_password);
        $user->first_login = false;
        $user->save();

        Session::put('first_login', false);

        return match($role) {
            'student' => redirect()->route('student.dashboard')->with('success', 'Password changed successfully'),
            'supervisor' => redirect()->route('supervisor.dashboard')->with('success', 'Password changed successfully'),
            'coordinator' => redirect()->route('coordinator.dashboard')->with('success', 'Password changed successfully'),
        };
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}
