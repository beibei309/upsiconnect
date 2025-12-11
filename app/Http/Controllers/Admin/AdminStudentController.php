<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // banned | active | null

        $students = User::where('role', 'student')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%");
                });
            })
            ->when($status === 'banned', function ($query) {
                $query->where('is_suspended', 1);
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_suspended', 0);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Keep filters when navigating pagination
        $students->appends($request->only('search', 'status'));

        return view('admin.students.index', compact('students', 'search', 'status'));
    }

    // VIEW STUDENT (PROFILE PAGE)
    public function view($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        return view('admin.students.view', compact('student'));
    }

    // EDIT STUDENT
    public function edit($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    // UPDATE STUDENT
    public function update(Request $request, $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'student_id' => 'nullable|string',
        ]);

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'student_id' => $request->student_id,
            'faculty' => $request->faculty,
            'course' => $request->course,
            'verification_status' => $request->verification_status,
        ]);


        return redirect()->route('admin.students.index')
                         ->with('success', 'Student updated successfully.');
    }

    // DELETE STUDENT
    public function destroy($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    // BAN STUDENT WITH REASON
    public function ban(Request $request, $id)
    {
        $request->validate([
            'blacklist_reason' => 'required|string|max:255'
        ]);

        $student = User::where('role', 'student')->findOrFail($id);
        $student->is_suspended = 1;
        $student->blacklist_reason = $request->blacklist_reason;
        $student->save();

        return redirect()->route('admin.students.index')
                         ->with('success', 'Student has been banned.');
    }

    // UNBAN STUDENT
    public function unban($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $student->is_suspended = 0;
        $student->blacklist_reason = null;
        $student->save();

        return redirect()->route('admin.students.index')
                         ->with('success', 'Student has been unbanned.');
    }

}