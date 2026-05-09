
@extends('layouts.admin')

@section('content')
<style>
    /* Stats Card Backgrounds (Unique Linear Gradients) */
    .stats-card {
        color: #fff;
        border: none;
        transition: transform 0.2s ease-in-out;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .stats-card h5, .stats-card h3 {
        color: #fff;
        font-weight: 600;
    }
    .stats-card .text-success {
        color: #a7f3d0 !important;
    }

    /* Unique gradients for each card */
    .stats-card.users { background: linear-gradient(135deg, #3b82f6, #93c5fd); } /* Blue for users */
    .stats-card.classes { background: linear-gradient(135deg, #10b981, #6ee7b7); } /* Green for classes */
    .stats-card.groups { background: linear-gradient(135deg, #f59e0b, #fcd34d); } /* Yellow for groups */
    .stats-card.categories { background: linear-gradient(135deg, #8b5cf6, #c4b5fd); } /* Purple for categories */
    .stats-card.resources { background: linear-gradient(135deg, #ef4444, #fca5a5); } /* Red for resources */

    /* Chart Card Styling */
    .chart-card {
        background: #f9fafb;
        background-image: url('data:image/svg+xml,%3Csvg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%239CA3AF" fill-opacity="0.1"%3E%3Ccircle cx="3" cy="3" r="1"/%3E%3Ccircle cx="17" cy="17" r="1"/%3E%3C/g%3E%3C/svg%3E');
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .chart-card h5 {
        color: #374151;
        font-weight: 600;
    }

    /* Responsive Chart Containers */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    /* Dropdown Styling */
    .chart-selector {
        max-width: 200px;
        margin-bottom: 1rem;
    }
</style>

<div class="container">
    <h2 class="mb-4">Admin Dashboard</h2>

    {{-- Stats Cards --}}
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3 stats-card users">
                <h5>Total Users</h5>
                <h3>{{ $stats['total_users'] }}</h3>
                <small class="text-success">{{ $stats['active_users'] }} active</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3 stats-card classes">
                <h5>Classes</h5>
                <h3>{{ $stats['classes'] }}</h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3 stats-card groups">
                <h5>Groups</h5>
                <h3>{{ $stats['groups'] }}</h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3 stats-card categories">
                <h5>Categories</h5>
                <h3>{{ $stats['categories'] }}</h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3 stats-card resources">
                <h5>Resources</h5>
                <h3>{{ $stats['resources'] }}</h3>
                <small class="text-success">{{ $stats['active_resources'] }} active</small>
            </div>
        </div>
    </div>

    {{-- Import Report (from Data Handler) --}}
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

    {{-- Chart Selection and Charts --}}
    <div class="row mt-4">
        <div class="col-12 mb-3">
            <select id="chartDataSelector" class="form-select chart-selector">
                <option value="resources">Resources by Type</option>
                <option value="users">Users by Role</option>
                <option value="classes">Classes by Department</option>
                <option value="groups">Groups by Category</option>
            </select>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-3 chart-card">
                <h5 id="barChartTitle">Resources by Type (Bar)</h5>
                <div class="chart-container">
                    <canvas id="resourceTypeBarChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-3 chart-card">
                <h5 id="pieChartTitle">Resources by Type (Pie)</h5>
                <div class="chart-container">
                    <canvas id="resourceTypePieChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-3 chart-card">
                <h5 id="scatterChartTitle">Resources by Type (Scatter)</h5>
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
    // Chart colors
    const colors = [
        'rgba(54, 162, 235, 0.6)', // Blue
        'rgba(255, 99, 132, 0.6)', // Red
        'rgba(75, 192, 192, 0.6)', // Teal
        'rgba(255, 205, 86, 0.6)', // Yellow
        'rgba(153, 102, 255, 0.6)', // Purple
        'rgba(255, 159, 64, 0.6)'  // Orange
    ];
    const borderColors = colors.map(color => color.replace('0.6', '1'));

    // Chart instances
    let barChart, pieChart, scatterChart;

    // Chart configurations
    function createBarChart(labels, data, title) {
        const ctx = document.getElementById('resourceTypeBarChart').getContext('2d');
        if (barChart) barChart.destroy();
        barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: title,
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
                    y: { beginAtZero: true, suggestedMax: Math.max(...data, 1) * 1.1 },
                    x: { ticks: { maxRotation: 45, minRotation: 45, autoSkip: true, maxTicksLimit: 10 } }
                }
            }
        });
        document.getElementById('barChartTitle').textContent = `${title} (Bar)`;
    }

    function createPieChart(labels, data, title) {
        const ctx = document.getElementById('resourceTypePieChart').getContext('2d');
        if (pieChart) pieChart.destroy();
        pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: title,
                    data: data,
                    backgroundColor: colors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });
        document.getElementById('pieChartTitle').textContent = `${title} (Pie)`;
    }

    function createScatterChart(labels, data, title) {
        const ctx = document.getElementById('resourceTypeScatterChart').getContext('2d');
        if (scatterChart) scatterChart.destroy();
        scatterChart = new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: title,
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
                    x: { title: { display: true, text: 'Index' }, ticks: { stepSize: 1 } },
                    y: { beginAtZero: true, title: { display: true, text: 'Count' }, suggestedMax: Math.max(...data, 1) * 1.1 }
                }
            }
        });
        document.getElementById('scatterChartTitle').textContent = `${title} (Scatter)`;
    }

    // Data sets from PHP
    const dataSets = {
        resources: {
            labels: {!! json_encode($stats['resourceTypeData']->pluck('name'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
            data: {!! json_encode($stats['resourceTypeData']->pluck('resources_count'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
            title: 'Resources by Type'
        },
        users: {
            labels: {!! json_encode($stats['userRoleData']->pluck('role'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
            data: {!! json_encode($stats['userRoleData']->pluck('user_count'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
            title: 'Users by Role'
        },
        classes: {
            labels: {!! json_encode($stats['classDepartmentData']->pluck('department'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
            data: {!! json_encode($stats['classDepartmentData']->pluck('class_count'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
            title: 'Classes by Department'
        },
        groups: {
            labels: {!! json_encode($stats['groupCategoryData']->pluck('category'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
            data: {!! json_encode($stats['groupCategoryData']->pluck('group_count'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
            title: 'Groups by Category'
        }
    };

    // Initialize charts with default data (resources)
    function initializeCharts(type = 'resources') {
        const { labels, data, title } = dataSets[type] || dataSets.resources;
        console.log(`Rendering ${type}:`, { labels, data });
        createBarChart(labels, data, title);
        createPieChart(labels, data, title);
        createScatterChart(labels, data, title);
    }

    // Event listener for data selector
    document.getElementById('chartDataSelector').addEventListener('change', function() {
        initializeCharts(this.value);
    });

    // Initial render
    initializeCharts();
</script>
@endsection
