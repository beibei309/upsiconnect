<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AdminCommunityController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status'); // add this

    $communityUsers = User::where('role', 'community')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->when($status === 'active', function ($query) {
            $query->where('is_blacklisted', 0);
        })
        ->when($status === 'blacklisted', function ($query) {
            $query->where('is_blacklisted', 1);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    $communityUsers->appends($request->only('search', 'status'));

    // Summary Stats
    $stats = [
        'total' => User::where('role', 'community')->count(),
        'approved' => User::where('role', 'community')->where('verification_status', 'approved')->count(),
        'pending' => User::where('role', 'community')->where('verification_status', 'pending')->count(),
        'blacklisted' => User::where('role', 'community')->where('is_blacklisted', 1)->count(),
    ];

    return view('admin.community.index', compact('communityUsers', 'stats', 'search', 'status'));
}


public function view($id)
{
    $user = User::where('role', 'community')->findOrFail($id);
    return view('admin.community.view', compact('user'));
}

public function edit($id)
{
    $user = User::where('role', 'community')->findOrFail($id);
    return view('admin.community.edit', compact('user'));
}

public function update(Request $request, $id)
{
    $user = User::where('role', 'community')->findOrFail($id);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->bio = $request->bio;
    $user->verification_status = $request->verification_status;

    // Upload new profile photo
    if ($request->hasFile('profile_photo')) {
        // Delete old photo if exists
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $file = $request->file('profile_photo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('uploads/profile', $filename, 'public');
        
        $user->profile_photo_path = $path;
    }

    // Blacklist / Unblacklist
    if ($request->remove_blacklist) {
        $user->is_blacklisted = 0;
        $user->blacklist_reason = null;
    } 
    elseif ($request->blacklist_reason) {
        $user->is_blacklisted = 1;
        $user->blacklist_reason = $request->blacklist_reason;
    }

    $user->save();

    return redirect()->route('admin.community.view', $user->id)
                     ->with('success', 'User updated successfully!');
}

public function blacklist(Request $request, $id)
{
    $request->validate([
        'blacklist_reason' => 'required|string|max:255'
    ]);

    $user = User::where('role', 'community')->findOrFail($id);

    $user->is_blacklisted = 1;
    $user->blacklist_reason = $request->blacklist_reason;
    $user->save();

    return redirect()->route('admin.community.index')
                     ->with('success', 'User has been blacklisted.');
}

public function unblacklist($id)
{
    $user = User::where('role', 'community')->findOrFail($id);

    $user->is_blacklisted = 0;
    $user->blacklist_reason = null;
    $user->save();

    return redirect()->route('admin.community.index')
                     ->with('success', 'Blacklist removed.');
}

}
