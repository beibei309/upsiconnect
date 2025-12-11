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
}