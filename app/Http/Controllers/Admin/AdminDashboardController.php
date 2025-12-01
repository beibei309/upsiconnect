<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentService;
use App\Models\ServiceApplication;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // REAL COUNTS
        $totalStudents = User::where('role', 'student')->count();
        $totalCommunityUsers = User::where('role', 'community')->count();
        $totalServices = StudentService::count();
        $pendingRequests = ServiceApplication::where('status', 'pending')->count();

        // MONTHLY STUDENT REGISTRATION (Line Chart)
        $studentMonthly = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('role', 'student')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total');

        // MONTHLY SERVICES CREATED (Bar Chart)
        $serviceMonthly = StudentService::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total');

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalCommunityUsers',
            'totalServices',
            'pendingRequests',
            'studentMonthly',
            'serviceMonthly'
        ));
    }
}
