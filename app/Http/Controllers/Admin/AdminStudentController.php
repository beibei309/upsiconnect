<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    // LIST STUDENTS
    public function index(Request $request)
{
    $query = User::where('role', 'student')->orderBy('created_at', 'desc');

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%")
              ->orWhere('phone', 'like', "%{$request->search}%")
              ->orWhere('student_id', 'like', "%{$request->search}%");
        });
    }

    $students = $query->paginate(10);

    // keep search value on pagination
    $students->appends($request->only('search'));

    return view('admin.students.index', compact('students'));
}



    // EDIT STUDENT
    public function edit($id)
    {
        $student = User::findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    // UPDATE STUDENT
    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'nullable',
            'student_id' => 'nullable'
        ]);

        $student->update($request->only([
            'name', 'email', 'phone', 'student_id', 'verification_status'
        ]));

        return redirect()->route('admin.students.index')
                         ->with('success', 'Student updated successfully.');
    }

        //delete 
        public function destroy($id)
        {
            $student = User::findOrFail($id);
            
            $student->delete();
            return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
        }

        //ban
        public function ban($id)
        {
            $student = User::findOrFail($id);
            
            $student->update(['is_suspended' => true]);
            
            return redirect()->route('admin.students.index')
            ->with('success', 'Student has been suspended.');
        }

        public function unban($id)
{
    $student = User::findOrFail($id);

    $student->update(['is_suspended' => false]);

    return redirect()->route('admin.students.index')
        ->with('success', 'Student has been unsuspended.');
}

}
