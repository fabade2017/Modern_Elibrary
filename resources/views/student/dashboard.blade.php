@extends('layouts.admin')

@section('content')

<style>


    .right-panel {
        position: fixed;
        top: 0;
        right: -400px; /* Hidden off-screen initially */
        width: 400px;
        height: 100vh; /* Full viewport height */
        overflow-y: auto; /* Allow scrolling if content overflows */
        transition: right 0.3s ease-in-out;
        z-index: 1000;
        background-color: #fff; /* Ensure background to avoid transparency */
    }
    .right-panel.open {
        right: 0;
    }
    .main-content {
        transition: margin-right 0.3s ease-in-out;
    }
    .main-content.panel-open {
        margin-right: 300px;
    }
    .toggle-button-container {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 1100; /* Ensure button is above panel */
    }
    @media (max-width: 768px) {
        .right-panel {
            width: 100%;
            right: -100%;
        }
        .main-content.panel-open {
            margin-right: 0;
        }
        .toggle-button-container {
            right: 15px;
            top: 15px;
        }
    }
    /* Hide default scrollbar for cleaner look */
    .right-panel::-webkit-scrollbar {
        display: none;
    }
    .right-panel {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }

    .calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
    margin-top: 10px;
}

.day, .empty {
    padding: 8px;
    border-radius: 6px;
    text-align: center;
    font-size: 0.9rem;
}

.day.today {
    background-color: #0d6efd;
    color: white;
    font-weight: bold;
}

.day:hover {
    background-color: #e9ecef;
    cursor: pointer;
}

.day-name {
    font-weight: bold;
    text-align: center;
    font-size: 0.8rem;
    color: #495057;
}

</style>
 
<div class="container">
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-12 main-content" id="mainContent">
            <h2 class="mb-4">My Library</h2>

         
            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('student.dashboard') }}" class="row mb-4">
                @csrf
                <div class="col-md-6">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search resources...">
                </div>
                <div class="col-md-4">
                    <select name="category_id" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <!-- Resources Grid -->
            <div class="row">
                @forelse($resources as $resourceAccess)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($resourceAccess->resource->file_path && in_array(pathinfo($resourceAccess->resource->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ asset('storage/' . $resourceAccess->resource->file_path) }}" alt="{{ $resourceAccess->resource->file_path }}" class="card-img-top" style="width: 100%; height: 150px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $resourceAccess->resource->title }}</h5>
                            <p class="card-text">{{ Str::limit($resourceAccess->resource->description, 100) }}</p>
                            <span class="badge bg-info text-dark">{{ ucfirst(optional($resourceAccess->resource->resourceType)->name ?? 'N/A') }}</span>
                            <div class="mt-3">
                                @if($resourceAccess->resource->view_online)
                                    <a href="{{ asset('storage/' . $resourceAccess->resource->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Open</a>
                                @endif
                                @if($resourceAccess->resource->downloadable && $resourceAccess->resource->file_path)
                                    <a href="{{ asset('storage/' . $resourceAccess->resource->file_path) }}" download class="btn btn-sm btn-outline-success">Download</a>
                                @endif
                                <a href="{{ route('student.resource.show', $resourceAccess->resource->id) }}" class="btn btn-sm btn-outline-info">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p>No resources found.</p>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $resources->withQueryString()->links() }}
            </div>
               <!-- Featured Section -->
            @if($featured->count())
            <div class="mb-5">
                <h3>🌟 Featured Resources</h3>
                <div class="row">
                    @foreach($featured as $resource)
                    <div class="col-md-4 mb-4">
                        <div class="card border-primary shadow-sm h-100">
                            @if($resource->file && in_array(pathinfo($resource->file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset('storage/' . $resource->file) }}" alt="{{ $resource->title }}" class="card-img-top" style="width: 100%; height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $resource->title }}</h5>
                                <p class="card-text">{{ Str::limit($resource->description, 80) }}</p>
                                <span class="badge bg-warning text-dark">{{ ucfirst(optional($resource->resourceType)->name ?? 'N/A') }}</span>
                                
                                <div class="mt-3">
                                    @if($resource->view_online)
                                        <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Open</a>
                                    @endif
                                    @if($resource->downloadable && $resource->file_path)
                                        <a href="{{ asset('storage/' . $resource->file_path) }}" download class="btn btn-sm btn-outline-success">Download</a>
                                    @endif
                                    <a href="{{ route('student.resource.show', $resource->id) }}" class="btn btn-sm btn-outline-info">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- Toggle Button -->
        <!-- <div class="toggle-button-container">
            <button class="btn btn-primary btn-sm" type="button" id="togglePanel">
                Show Panel
            </button>
        </div> -->

        <!-- Right Panel -->
        <div class="right-panel" id="rightPanel">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Quick Access</h5>
                    
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header navbar navbar-expand-lg navbar-dark text-white">🏆 Top Learners This Week</div>
                        <ul class="list-group list-group-flush">
                            @foreach($topUsers as $index => $user)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong>#{{ $index+1 }}</strong> {{ $user->name }}
                                    </span>
                                    <span class="badge bg-success">{{ $user->points }} pts</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header navbar navbar-expand-lg navbar-dark text-white">📊 My Progress</div>
                        <div class="card-body text-center">
                            <h5>Level {{ auth()->user()->level }}</h5>
                            <p>{{ auth()->user()->points }} points</p>
                            <p>🔥 Streak: {{ auth()->user()->streak }} days</p>

                            <!-- Progress Bar -->
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                    role="progressbar" 
                                    style="width: {{ (auth()->user()->points % 100) }}%" 
                                    aria-valuenow="{{ (auth()->user()->points % 100) }}" 
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ 100 - (auth()->user()->points % 100) }} pts to next level
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header navbar navbar-expand-lg navbar-dark">🎖️ My Badges</div>
                        <div class="card-body d-flex flex-wrap gap-3">
                            @forelse(auth()->user()->badges as $badge)
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $badge->icon) }}" 
                                        alt="{{ $badge->name }}" 
                                        style="width:50px;height:50px;">
                                    <p class="small">{{ $badge->name }}</p>
                                </div>
                            @empty
                                <p>No badges earned yet. Keep learning! 🚀</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="card mb-4 shadow-sm">
    <div class="card-header navbar navbar-expand-lg navbar-dark">📅 Learning Calendar</div>
    <div class="card-body text-center">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <button class="btn btn-sm btn-outline-secondary" onclick="prevMonth()">◀</button>
            <h6 id="month-year" class="mb-0"></h6>
            <button class="btn btn-sm btn-outline-secondary" onclick="nextMonth()">▶</button>
        </div>
        <div id="calendar" class="calendar-grid">
            <!-- Days will be injected by JS -->
            <div class="day-name">Sun</div>
            <div class="day-name">Mon</div>
            <div class="day-name">Tue</div>
            <div class="day-name">Wed</div>
            <div class="day-name">Thu</div>
            <div class="day-name">Fri</div>
            <div class="day-name">Sat</div>
        </div>
    </div>
</div>

                        <h6>Useful Links</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-decoration-none">Library Guide</a></li>
                            <li><a href="#" class="text-decoration-none">Support</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.querySelector('#togglePanel');
        const rightPanel = document.querySelector('#rightPanel');
        const mainContent = document.querySelector('#mainContent');

        toggleButton.addEventListener('click', function () {
            rightPanel.classList.toggle('open');
            mainContent.classList.toggle('panel-open');
            toggleButton.textContent = rightPanel.classList.contains('open') ? 'Hide Panel' : 'Show Panel';
        });
    });
</script>
 <script>
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        function renderCalendar() {
            const calendar = document.getElementById("calendar");
            const monthYear = document.getElementById("month-year");
            monthYear.textContent = `${monthNames[currentMonth]} ${currentYear}`;

            // Clear previous days
            while (calendar.children.length > 7) {
                calendar.removeChild(calendar.lastChild);
            }

            // Get first day of the month
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            // Add empty slots for days before the first day
            for (let i = 0; i < firstDay; i++) {
                const emptyDiv = document.createElement("div");
                emptyDiv.className = "empty";
                calendar.appendChild(emptyDiv);
            }

            // Add days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayDiv = document.createElement("div");
                dayDiv.className = "day";
                dayDiv.textContent = day;
                if (
                    day === currentDate.getDate() &&
                    currentMonth === currentDate.getMonth() &&
                    currentYear === currentDate.getFullYear()
                ) {
                    dayDiv.className += " today";
                }
                calendar.appendChild(dayDiv);
            }
        }

        function prevMonth() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        }

        function nextMonth() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        }

        // Initial render
        renderCalendar();
    </script>
@endsection