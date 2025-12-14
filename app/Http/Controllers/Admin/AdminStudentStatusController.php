<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentStatus;
use App\Models\User;
use Illuminate\Http\Request;

class AdminStudentStatusController extends Controller
{
    // LIST ALL STATUS
    public function index()
    {
        $statuses = StudentStatus::with('student')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.student_status.index', compact('statuses'));
    }

    // SHOW CREATE FORM
    public function create()
    {
        $students = User::where('role', 'student')->get();

        return view('admin.student_status.create', compact('students'));
    }

    // STORE NEW STATUS
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'semester' => 'required',
            'status' => 'required',
        ]);

        StudentStatus::create([
            'student_id' => $request->student_id,
            'matric_no' => User::find($request->student_id)->student_id,
            'semester' => $request->semester,
            'status' => $request->status,
            'effective_date' => now()->toDateString(),  // AUTO DATE
        ]);

        return redirect()->route('admin.student_status.index')
            ->with('success', 'Student status added successfully.');
    }

    // EDIT
    public function edit($id)
    {
        $status = StudentStatus::findOrFail($id);
        $students = User::where('role', 'student')->get();

        return view('admin.student_status.edit', compact('status', 'students'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required',
            'semester' => 'required',
            'status' => 'required',
        ]);

        $status = StudentStatus::findOrFail($id);

        $status->update([
            'student_id' => $request->student_id,
            'matric_no' => User::find($request->student_id)->student_id,
            'semester' => $request->semester,
            'status' => $request->status,
            'effective_date' => now()->toDateString(), // AUTO UPDATE DATE
        ]);

        return redirect()->route('admin.student_status.index')
            ->with('success', 'Student status updated.');
    }

    // DELETE
    public function destroy($id)
    {
        StudentStatus::findOrFail($id)->delete();

        return redirect()->route('admin.student_status.index')
            ->with('success', 'Student status deleted.');
    }
}
