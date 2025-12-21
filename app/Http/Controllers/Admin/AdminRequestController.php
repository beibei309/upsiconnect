<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class AdminRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceRequest::with(['requester', 'provider', 'studentService']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('requester', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhereHas('studentService', function($q) use ($search) {
                $q->where('title', 'like', "%$search%");
            });
        }

        $requests = $query->latest()->paginate(10);

        return view('admin.requests.index', compact('requests'));
    }

    public function export(Request $request)
{
    $query = \App\Models\ServiceRequest::with(['requester', 'provider', 'studentService']);

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('requester', fn($q) => $q->where('name', 'like', "%{$search}%"))
              ->orWhereHas('studentService', fn($q) => $q->where('title', 'like', "%{$search}%"));
    }

    // Apply status filter
    if ($request->filled('status')) {
        $status = $request->status;
        $query->where('status', $status);
    }

    $requests = $query->get();

    $csvData = $requests->map(function ($r) {
        return [
            'Requester' => $r->requester->name,
            'Service' => $r->studentService->title,
            'Provider' => $r->provider->name,
            'Request Date' => $r->created_at->format('d/m/Y'),
            'Price' => number_format($r->studentService->suggested_price, 2),
            'Status' => $r->status,
        ];
    });

    return response()->streamDownload(function () use ($csvData) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array_keys($csvData->first()));
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }, 'service_requests.csv');
}

}