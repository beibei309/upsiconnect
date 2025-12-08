@extends('admin.layout')

@section('content')

<div class="px-4 md:px-8">

    <!-- Title -->
    <h1 class="text-4xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500 mt-1">Monitor platform activity and analytics.</p>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-10">

        <!-- CARD: Total Students -->
    <a href="{{ route('admin.students.index') }}"
       class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl hover:scale-[1.02] transition border border-gray-100 block">
        <p class="text-gray-500 font-medium">Total Students</p>
        <p class="text-5xl font-bold text-blue-600 mt-2">{{ $totalStudents }}</p>
    </a>

    <!-- CARD: Total Community Users -->
    <a href="{{ route('admin.community.index') }}"
       class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl hover:scale-[1.02] transition border border-gray-100 block">
        <p class="text-gray-500 font-medium">Total Community Users</p>
        <p class="text-5xl font-bold text-purple-600 mt-2">{{ $totalCommunityUsers }}</p>
    </a>

    <!-- CARD: Total Services -->
    <a href="{{ route('admin.services.index') }}"
       class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl hover:scale-[1.02] transition border border-gray-100 block">
        <p class="text-gray-500 font-medium">Total Services</p>
        <p class="text-5xl font-bold text-pink-600 mt-2">{{ $totalServices }}</p>
    </a>

    <!-- CARD: Pending Requests -->
    <a href="#"
       class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl hover:scale-[1.02] transition border border-gray-100 block">
        <p class="text-gray-500 font-medium">Pending Requests</p>
        <p class="text-5xl font-bold text-yellow-600 mt-2">{{ $pendingRequests }}</p>
    </a>

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
    const monthLabels = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    // Convert PHP → JS safely
    const studentData = @json(array_values($studentsPerMonth));
    const serviceData = @json(array_values($servicesPerMonth));

    // LINE CHART (Students)
    new Chart(document.getElementById('studentChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: "Students Registered",
                data: studentData,
                fill: true,
                tension: 0.3
            }]
        }
    });

    // BAR CHART (Services)
    new Chart(document.getElementById('serviceChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: "Services Created",
                data: serviceData
            }]
        }
    });
</script>
@endsection
