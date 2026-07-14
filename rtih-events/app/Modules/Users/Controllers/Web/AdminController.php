<?php

namespace App\Modules\Users\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Modules\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        // Only super-admins can manage other admins
        abort_unless(auth()->user()->hasRole('super-admin'), 403);
        
        $admins = User::with('roles')->latest()->paginate(25);
        $roles = Role::all();
        return view('admins.index', compact('admins', 'roles'));
    }

    public function store(\App\Http\Requests\StoreAdminRequest $request, \App\Services\AdminService $adminService)
    {
        $adminService->createAdmin($request->validated());

        return back()->with('success', 'User created successfully.');
    }

    public function update(\App\Http\Requests\UpdateAdminRequest $request, User $admin, \App\Services\AdminService $adminService)
    {
        $adminService->updateAdminRole($admin, $request->validated('role'));

        return back()->with('success', 'User role updated.');
    }

    public function destroy(User $admin)
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);
        
        if ($admin->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete yourself.']);
        }
        
        $admin->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
