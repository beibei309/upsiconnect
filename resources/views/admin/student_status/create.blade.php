@extends('admin.layout')

@section('content')

<<<<<<< HEAD
<h1 class="text-3xl font-bold mb-6">Add Student Status</h1>

<form action="{{ route('admin.student_status.store') }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf

    <div class="mb-4">
        <label class="font-semibold block mb-1">Student</label>
        <select name="student_id" class="border p-2 rounded w-full" required>
            <option value="">Select Student</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_id }})</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="font-semibold block mb-1">Semester</label>
        <input type="text" name="semester" class="border p-2 rounded w-full"
               placeholder="Semester 1 2025" required>
    </div>

    <div class="mb-4">
        <label class="font-semibold block mb-1">Status</label>
        <select name="status" class="border p-2 rounded w-full" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
            <option value="Deferred">Deferred</option>
        </select>
    </div>

    <p class="text-gray-600 mb-3 text-sm">
        * Effective date will be automatically set to today.
    </p>

    <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>

    <!-- CANCEL (BACK TO LIST PAGE) -->
        <a href="{{ route('admin.student_status.index') }}"
           class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
            Cancel
        </a>
    </div>

</form>
=======
<div class="max-w-5xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Add New Student Status</h2>
        <a href="{{ route('admin.student_status.index') }}"
           class="text-gray-600 hover:text-gray-900 text-sm">
            &larr; Back to List
        </a>
    </div>

    <form action="{{ route('admin.student_status.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- LEFT COLUMN : STUDENT SELECTION --}}
            <div class="md:col-span-1">
                <label class="block text-gray-700 font-bold mb-2">
                    1. Select Student / Helper
                </label>

                <div class="bg-white border border-gray-300 shadow-sm rounded-lg overflow-hidden h-[450px] flex flex-col">

                    <div class="bg-blue-600 px-4 py-3 border-b border-blue-700 shrink-0">
                        <h3 class="text-white font-medium text-sm">
                            Available Students
                        </h3>
                    </div>

                    <div class="overflow-y-auto flex-1">
                        @if($students->isEmpty())
                            <div class="p-4 text-center text-gray-500 text-sm">
                                No students found.
                            </div>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($students as $student)
                                    <li class="hover:bg-blue-50 transition">
                                        <label class="flex items-center justify-between px-4 py-3 cursor-pointer">
                                            <div>
                                                <span class="block text-gray-900 font-medium text-sm">
                                                    {{ $student->name }}
                                                </span>
                                                <span class="block text-gray-500 text-xs">
                                                    {{ $student->student_id ?? 'No Matric' }}
                                                </span>
                                            </div>

                                            <input type="radio"
                                                   name="student_id"
                                                   value="{{ $student->id }}"
                                                   required
                                                   class="form-radio text-blue-600 h-4 w-4">
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                @error('student_id')
                    <p class="text-red-500 text-xs mt-1">Please select a student.</p>
                @enderror
            </div>

            {{-- RIGHT COLUMN : STATUS DETAILS --}}
            <div class="md:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 h-full">

                    <h3 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-2">
                        Status Details
                    </h3>

                    <div class="space-y-6">

                        {{-- STATUS --}}
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                2. Academic Status
                            </label>

                            <select name="status"
                                    id="status"
                                    required
                                    class="w-full border-gray-300 rounded-md shadow-sm p-2.5
                                           focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled selected>
                                    -- Select Academic Status --
                                </option>
                                <option value="Active">Active</option>
                                <option value="Probation">Probation</option>
                                <option value="Deferred">Deferred</option>
                                <option value="Graduated">Graduated</option>
                                <option value="Dismissed">Dismissed</option>
                            </select>

                            <p class="text-gray-400 text-xs mt-1">
                                Select status first to adjust other fields.
                            </p>

                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SEMESTER --}}
                        <div id="semester-container">
                            <label class="block text-gray-700 font-medium mb-2">
                                3. Current Semester
                            </label>

                            <select name="semester"
                                    id="semester"
                                    class="w-full border-gray-300 rounded-md shadow-sm p-2.5
                                           focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Select Semester --</option>

                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="Semester {{ $i }}">
                                        Semester {{ $i }}
                                    </option>
                                @endfor

                                <option value="Extended">Extended</option>
                            </select>

                            @error('semester')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- GRADUATION / COMPLETION DATE --}}
                        <div id="graduation-date-container">
                            <label class="block text-gray-700 font-medium mb-1">
                                4. Expected Completion / Graduation Date
                            </label>

                            <p class="text-xs text-gray-500 mb-2">
                                If active, select expected completion date.
                                If graduated, select actual graduation date.
                            </p>

                            <input type="date"
                                   name="graduation_date"
                                   class="w-full border-gray-300 rounded-md shadow-sm p-2.5
                                          focus:ring-blue-500 focus:border-blue-500">

                            @error('graduation_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- SUBMIT --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 text-white font-bold py-2.5 px-6 rounded
                                       hover:bg-blue-700 transition shadow">
                            Save Status Record
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>

{{-- TOGGLE SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const status = document.getElementById('status');
    const semesterBox = document.getElementById('semester-container');
    const dateBox = document.getElementById('graduation-date-container');

    function toggleFields() {
        const value = status.value;

        // Semester logic
        if (value === 'Graduated' || value === 'Dismissed') {
            semesterBox.style.display = 'none';
        } else {
            semesterBox.style.display = 'block';
        }

        // Date logic
        if (value === 'Dismissed') {
            dateBox.style.display = 'none';
        } else {
            dateBox.style.display = 'block';
        }
    }

    toggleFields();
    status.addEventListener('change', toggleFields);
});
</script>
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6

@endsection
