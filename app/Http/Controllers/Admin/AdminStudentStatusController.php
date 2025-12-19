<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
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
=======
use App\Models\User;
use App\Models\StudentStatus;
use Illuminate\Http\Request;
use App\Notifications\StudyDurationReminder; // Make sure this import exists

class AdminStudentStatusController extends Controller
{
    // 1. DISPLAY ALL STUDENTS & HELPERS WITH STATUS
    public function index(Request $request)
    {
        $filter = $request->input('grad_filter');
        $search = $request->input('search');

        $students = User::whereIn('role', ['student', 'helper'])
            ->with('studentStatus')

            // SEARCH
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%");
                });
            })

            // GRADUATION FILTER
            ->when($filter, function ($query, $filter) {
                $query->whereHas('studentStatus', function ($q) use ($filter) {

                    $q->whereNotNull('graduation_date')
                      ->where('status', '!=', 'Graduated');

                    if ($filter === 'expired') {
                        $q->whereDate('graduation_date', '<', today());
                    } elseif ($filter === '3_months') {
                        $q->whereBetween('graduation_date', [today(), today()->addMonths(3)]);
                    } elseif ($filter === '6_months') {
                        $q->whereBetween('graduation_date', [today(), today()->addMonths(6)]);
                    } elseif ($filter === '12_months') {
                        $q->whereBetween('graduation_date', [today(), today()->addMonths(12)]);
                    }
                });
            })

            ->orderBy('name', 'asc')
            ->paginate(10);

        $students->appends($request->all());

        return view('admin.student_status.index', compact('students'));
    }

    // 2. SHOW CREATE FORM
    public function create(Request $request)
    {
        $existingStatusIds = StudentStatus::pluck('student_id')->toArray();

        $students = User::whereIn('role', ['student', 'helper'])
            ->whereNotIn('id', $existingStatusIds)
            ->orderBy('name', 'asc')
            ->get();

        $selectedStudentId = $request->input('student_id');

        return view('admin.student_status.create', compact('students', 'selectedStudentId'));
    }

    // 3. STORE NEW STATUS
    public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required|unique:student_statuses,student_id',
        'status' => 'required|in:Active,Probation,Deferred,Graduated,Dismissed',
        'semester' => 'nullable|string',
        'graduation_date' => 'nullable|date',
    ]);

    $student = User::findOrFail($request->student_id);

    // LOGIC VALIDATION
    if ($request->status === 'Graduated' && !$request->graduation_date) {
        return back()
            ->withInput()
            ->withErrors([
                'graduation_date' => 'Graduation date is required for graduated students.'
            ]);
    }

    if ($request->status === 'Dismissed') {
        $request->merge([
            'semester' => null,
            'graduation_date' => null,
        ]);
    }

    StudentStatus::create([
        'student_id' => $student->id,
        'matric_no' => $student->student_id,
        'semester' => in_array($request->status, ['Graduated', 'Dismissed'])
            ? null
            : $request->semester,
        'status' => $request->status,
        'effective_date' => now(),
        'graduation_date' => $request->graduation_date,
    ]);

    return redirect()
        ->route('admin.student_status.index')
        ->with('success', 'Student status created successfully.');
}

    // 4. SHOW EDIT FORM
    public function edit($id)
    {
        $status = StudentStatus::findOrFail($id);

        $students = User::whereIn('role', ['student', 'helper'])->get();
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6

        return view('admin.student_status.edit', compact('status', 'students'));
    }

<<<<<<< HEAD
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
=======
    // 5. UPDATE STATUS
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'semester' => 'nullable|string',
            'graduation_date' => 'nullable|date',
        ]);

        $statusRecord = StudentStatus::findOrFail($id);

        $statusRecord->update([
            'semester' => $request->semester,
            'status' => $request->status,
            'graduation_date' => $request->graduation_date,
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
        ]);

        return redirect()->route('admin.student_status.index')
            ->with('success', 'Student status updated.');
    }

<<<<<<< HEAD
    // DELETE
=======
    // 6. DELETE STATUS
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
    public function destroy($id)
    {
        StudentStatus::findOrFail($id)->delete();

        return redirect()->route('admin.student_status.index')
<<<<<<< HEAD
            ->with('success', 'Student status deleted.');
    }
}
=======
            ->with('success', 'Status deleted.');
    }

}
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
