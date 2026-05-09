
@extends('layouts.admin')

@section('content')
<style>
    /* Stats Card Backgrounds (Linear Gradients) */
    .stats-card {
        background: linear-gradient(135deg, #6b7280, #d1d5db); /* Gray gradient for a professional look */
        color: #fff; /* White text for contrast */
        border: none;
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-5px); /* Subtle lift effect on hover */
    }
    .stats-card h5, .stats-card h3 {
        color: #fff;
    }
    .stats-card .text-success {
        color: #a7f3d0 !important; /* Lighter green for active counts */
    }

    /* Chart Card Backgrounds (Subtle Pattern) */
    .chart-card {
        background: #f9fafb; /* Light base color */
        background-image: url('data:image/svg+xml,%3Csvg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%239CA3AF" fill-opacity="0.1"%3E%3Ccircle cx="3" cy="3" r="1"/%3E%3Ccircle cx="17" cy="17" r="1"/%3E%3C/g%3E%3C/svg%3E'); /* Subtle dot pattern */
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .chart-card h5 {
        color: #374151; /* Dark gray for chart titles */
    }

    /* Ensure canvas containers are responsive */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>

<div class="container">
    <h2 class="mb-4">Admin Dashboard</h2>

    {{-- Stats Cards --}}
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3 stats-card">
                <h5>Total Users</h5>
                <h3>{{ $stats['total_users'] }}</h3>
                <small class="text-success">{{ $stats['active_users'] }} active</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3 stats-card">
                <h5>Classes</h5>
                <h3>{{ $stats['classes'] }}</h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3 stats-card">
                <h5>Groups</h5>
                <h3>{{ $stats['groups'] }}</h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3 stats-card">
                <h5>Categories</h5>
                <h3>{{ $stats['categories'] }}</h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3 stats-card">
                <h5>Resources</h5>
                <h3>{{ $stats['resources'] }}</h3>
                <small class="text-success">{{ $stats['active_resources'] }} active</small>
            </div>
        </div>
    </div>

    <div>
        @if(session('import_report'))
            <div class="alert alert-info">
                <p><strong>Import Summary</strong></p>
                <p>Created: {{ session('import_report')['created'] }}</p>
                <p>Skipped Duplicates: {{ session('import_report')['skipped_duplicates'] }}</p>
                @if(count(session('import_report')['validation_errors']))
                    <ul>
                        @foreach(session('import_report')['validation_errors'] as $error)
                            <li>Row {{ $error['row'] }} - {{ implode(', ', $error['errors']) }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
    </div>

    {{-- Charts Section --}}
    <div class="row mt-4">
        {{-- Bar Chart --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-3 chart-card">
                <h5>Resources by Type (Bar)</h5>
                <div class="chart-container">
                    <canvas id="resourceTypeBarChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Pie Chart --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-3 chart-card">
                <h5>Resources by Type (Pie)</h5>
                <div class="chart-container">
                    <canvas id="resourceTypePieChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Scatter Plot --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-3 chart-card">
                <h5>Resources by Type (Scatter)</h5>
                <div class="chart-container">
                    <canvas id="resourceTypeScatterChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    // Shared chart colors
    const colors = [
        'rgba(54, 162, 235, 0.6)',
        'rgba(255, 99, 132, 0.6)',
        'rgba(75, 192, 192, 0.6)',
        'rgba(255, 205, 86, 0.6)',
        'rgba(153, 102, 255, 0.6)',
        'rgba(255, 159, 64, 0.6)'
    ];
    const borderColors = colors.map(color => color.replace('0.6', '1'));

    // Bar Chart
    function createBarChart(labels, data) {
        const ctx = document.getElementById('resourceTypeBarChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Resources',
                    data: data,
                    backgroundColor: colors[0],
                    borderColor: borderColors[0],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...data) * 1.1
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 10
                        }
                    }
                }
            }
        });
    }

    // Pie Chart
    function createPieChart(labels, data) {
        const ctx = document.getElementById('resourceTypePieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Resources',
                    data: data,
                    backgroundColor: colors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    }

    // Scatter Plot
    function createScatterChart(labels, data) {
        const ctx = document.getElementById('resourceTypeScatterChart').getContext('2d');
        new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Resources',
                    data: data.map((value, index) => ({ x: index + 1, y: value })),
                    backgroundColor: colors[0],
                    borderColor: borderColors[0],
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Resource Type Index'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Resource Count'
                        },
                        suggestedMax: Math.max(...data) * 1.1
                    }
                }
            }
        });
    }

    // Pass PHP data to JavaScript
    const resourceLabels = {!! json_encode($stats['resourceTypeData']->pluck('name'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
    const resourceData = {!! json_encode($stats['resourceTypeData']->pluck('resources_count'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};

    // Debug data
    console.log('Labels:', resourceLabels);
    console.log('Data:', resourceData);

    // Initialize all charts
    createBarChart(resourceLabels, resourceData);
    createPieChart(resourceLabels, resourceData);
    createScatterChart(resourceLabels, resourceData);
</script>
@endsection
