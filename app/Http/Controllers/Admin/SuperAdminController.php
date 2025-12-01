<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\StudentService;
use App\Models\ServiceApplication;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        // Stats
        $totalStudents = User::where('role', 'student')->count();
        $totalCommunityUsers = User::where('role', 'community')->count();
        $totalServices = StudentService::count();
        $pendingRequests = ServiceApplication::where('status', 'pending')->count();

        // All admins
        $admins = Admin::orderBy('role')->get();

        return view('admin.superdashboard', compact(
            'totalStudents',
            'totalCommunityUsers',
            'totalServices',
            'pendingRequests',
            'admins'
        ));
    }

    public function adminsIndex()
{
    $admins = \App\Models\Admin::orderBy('role')->get();

    return view('admin.admins.index', compact('admins'));
}

public function create()
{
    return view('admin.admins.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:admins,email',
        'password' => 'required|min:6',
        'role' => 'required'
    ]);

    \App\Models\Admin::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role,
    ]);

    return redirect()->route('admin.super.admins.index')
                     ->with('success', 'Admin created successfully.');
}

public function edit($id)
{
    $admin = \App\Models\Admin::findOrFail($id);

    return view('admin.admins.edit', compact('admin'));
}

public function update(Request $request, $id)
{
    $admin = \App\Models\Admin::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:admins,email,' . $id,
        'role' => 'required'
    ]);

    $admin->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
    ]);

    if ($request->password) {
        $admin->update(['password' => bcrypt($request->password)]);
    }

    return redirect()->route('admin.super.admins.index')
                     ->with('success', 'Admin updated successfully.');
}

public function destroy($id)
{
    $admin = \App\Models\Admin::findOrFail($id);
    $admin->delete();

    return redirect()->route('admin.super.admins.index')
                     ->with('success', 'Admin deleted successfully.');
}

}
