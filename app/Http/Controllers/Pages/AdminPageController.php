<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class AdminPageController extends Controller
{
    public function verifications()
    {
        // Only allow staff/admin roles
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['staff', 'admin'])) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $pending = User::query()
            ->where('role', 'community')
            ->where('verification_status', 'pending')
            ->orderBy('created_at')
            ->paginate(20);

        return view('admin.verifications.index', [
            'pending' => $pending,
        ]);
    }

    public function reports()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['staff', 'admin'])) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $reports = Report::query()
            ->where('status', 'open')
            ->with(['reporter', 'target'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.reports.index', [
            'reports' => $reports,
        ]);
    }
}