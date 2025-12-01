@extends('admin.layout')

@section('content')

<div class="px-4 md:px-8">

    <!-- Title -->
    <h1 class="text-4xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500 mt-1">Monitor platform activity and analytics.</p>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-10">

        <!-- CARD -->
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition border border-gray-100">
            <p class="text-gray-500 font-medium">Total Students</p>
            <p class="text-5xl font-bold text-blue-600 mt-2">{{ $totalStudents }}</p>
        </div>

        <!-- CARD -->
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition border border-gray-100">
            <p class="text-gray-500 font-medium">Total Community Users</p>
            <p class="text-5xl font-bold text-purple-600 mt-2">{{ $totalCommunityUsers }}</p>
        </div>

        <!-- CARD -->
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition border border-gray-100">
            <p class="text-gray-500 font-medium">Total Services</p>
            <p class="text-5xl font-bold text-pink-600 mt-2">{{ $totalServices }}</p>
        </div>

        <!-- CARD -->
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition border border-gray-100">
            <p class="text-gray-500 font-medium">Pending Requests</p>
            <p class="text-5xl font-bold text-yellow-600 mt-2">{{ $pendingRequests }}</p>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">

        <!-- LINE CHART -->
        <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Monthly Student Registrations</h2>
            <canvas id="studentChart" height="120"></canvas>
        </div>

        <!-- BAR CHART -->
        <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Services Created Per Month</h2>
            <canvas id="serviceChart" height="120"></canvas>
        </div>

    </div>

</div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];

    // Student Registration Chart (Line)
    new Chart(document.getElementById('studentChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Students',
                data: @json($studentMonthly),,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.15)',
                borderWidth: 3,
                tension: 0.4,
                pointBorderWidth: 3,
                pointRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Service Count Chart (Bar)
    new Chart(document.getElementById('serviceChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Services',
                data: [8, 12, 9, 16, 20, 18],
                backgroundColor: '#9333ea',
                borderRadius: 10,
                barThickness: 35
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection
