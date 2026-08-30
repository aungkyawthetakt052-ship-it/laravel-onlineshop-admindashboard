<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = strtolower(str_replace(' ', '', $request->search));

            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(REPLACE(name, ' ', '')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("LOWER(REPLACE(email, ' ', '')) LIKE ?", ["%{$search}%"]);
            });
        }

        $users = $query
            ->orderByRaw("
            CASE 
                WHEN user_type = 'superadmin' THEN 0
                WHEN user_type = 'admin' THEN 1
                ELSE 2
            END
        ")
            ->orderBy('created_at', 'desc')
            ->get();

        return view("adminview.users", compact("users"));
    }

    public function userStatus(int $id)
    {
        $user = User::findOrFail($id);

        if (!$user->canBeManagedBy(Auth::user())) {
            return redirect()->route('users')->with('error', 'You do not have permission to change this user\'s status.');
        }

        $user->is_active = $user->is_active == 1 ? 0 : 1;
        $user->save();

        $message = $user->is_active == 1 ? 'User activated successfully' : 'User disabled successfully';
        return redirect()->route('users')->with('success', $message);
    }
    public function registerPage()
    {
        return view('profile.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'user_type' => 'user',
            'is_active' => 1,
        ]);

        // Admin + Superadmin နှစ်ခုလုံးကို ပို့
        $admins = User::whereIn('user_type', ['admin', 'superadmin'])->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewUserNotification($newUser));
        }

        return redirect('/')->with('success', 'Register successfully');
    }

    public function loginPage()
    {
        return view('profile.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Disabled user ကို login မဝင်ခွင့်
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account has been disabled. Please contact admin.',
                ])->onlyInput('email');
            }

            // Admin က user login page ကနေ ဝင်လာရင် ပိတ်မယ် (optional)
            if ($user->user_type === 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Please use the Admin Login page.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password',
        ])->onlyInput('email');
    }
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


    public function userDetail(int $id)
    {
        $user = User::findOrFail($id);
        $canManage = $user->canBeManagedBy(Auth::user());

        return view('userAuth.userdetail', compact('user', 'canManage'));
    }
    public function profileEditPage()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'status' => 'nullable|string|max:150',
            'photo' => 'nullable|image|max:5012',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;

        // Photo upload
        if ($request->hasFile('photo')) {
            // အဟောင်း photo ရှိရင် ဖျက်မယ်
            if ($user->photo && File::exists(public_path('images/profile/' . $user->photo))) {
                File::delete(public_path('images/profile/' . $user->photo));
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/profile'), $fileName);
            $user->photo = $fileName;
        }

        if ($request->filled('password')) {
            $user->password = $request->password;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully');
    }
}