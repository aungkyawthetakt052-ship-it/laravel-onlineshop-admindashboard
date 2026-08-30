<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //   admin login page
    public function adminLoginPage()
    {
        return view('adminlogin.login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // ===== admin OR superadmin ၂ မျိုးလုံး လက်ခံမယ် =====
            if (!in_array(Auth::user()->user_type, ['admin', 'superadmin'])) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'You are not authorized to access admin panel.',
                ])->onlyInput('email');
            }

            return redirect()->route('admin.store');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password',
        ])->onlyInput('email');
    }
    // End  admin login page




    // Admin create
    public function createPage()
    {
        return view('userAuth.create');
    }
    public function create(Request $request)
    {
        $currentUser = Auth::user();
        $allowedTypes = $currentUser && $currentUser->user_type === 'superadmin'
            ? ['user', 'admin', 'superadmin']
            : ['user'];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'user_type' => 'required|in:' . implode(',', $allowedTypes),
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>$request->password,  
            'user_type' => $request->user_type,
        ]);
        return redirect()->route('users')->with('success', 'User create successfully');
    }
    // End Admin create


    //Admin User Edit

    public function userUpdatePage(int $id)
    {
        $user = User::findOrFail($id);

        if (!$user->canBeManagedBy(Auth::user())) {
            return redirect()->route('users')->with('error', 'You do not have permission to edit this user.');
        }

        return view('userAuth.edit', compact('user'));
    }
    public function userUpdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        if (!$user->canBeManagedBy(Auth::user())) {
            return redirect()->route('users')->with('error', 'You do not have permission to edit this user.');
        }

        $currentUser = Auth::user();
        $allowedTypes = $currentUser && $currentUser->user_type === 'superadmin'
            ? ['user', 'admin', 'superadmin']
            : ['user'];

        $request->validate([
            'name' => 'required|max:90',
            'email' => 'required|email|unique:users,email,' . $id,
            'user_type' => 'required|in:' . implode(',', $allowedTypes),
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => $request->user_type,
        ]);

        return redirect()->route('users')->with('success', 'User updated successfully');
    }
    // End AdminUser Edit

    // AdminUser delete 

    public function userDelete(int $id)
    {
        $user = User::findOrFail($id);

        if (!$user->canBeManagedBy(Auth::user())) {
            return redirect()->route('users')->with('error', 'You do not have permission to delete this user.');
        }

        $user->delete();
        return redirect()->route('users')->with('success', 'User deleted successfully');
    }
    // End Admin User delete 

    // Admin dashbardpage

    public function adminDashboard()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('user_type', ['admin','superadmin'])->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        return view('adminview.home', compact(
            'totalUsers',
            'totalAdmins',
            'totalProducts',
            'totalOrders'
        ));
    }
    // Admin dashbardpage
}