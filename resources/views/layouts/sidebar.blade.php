<!-- Sidebar Toggle Button -->
<button class="btn btn-primary d-md-none position-fixed" id="sidebarToggle">
    <span id="toggleIcon">☰</span>
</button>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar bg-dark text-white">
    <!-- Sidebar Header with Close Button -->
    <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom border-secondary">
        <h5 class="mb-0">📱 VUFYPMS</h5>
        <button class="sidebar-close-btn" id="sidebarCloseBtn">✕</button>
    </div>

    <!-- User Info -->
    <div class="user-info p-3 border-bottom border-secondary">
        <div class="small">{{ Auth::user()->name }}</div>
        <div class="text-muted small text-uppercase">{{ ucfirst(Auth::user()->role) }}</div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav flex-grow-1 overflow-y-auto">
        <ul class="nav flex-column p-2">

            {{-- DASHBOARD (All Roles) --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    🏠 Dashboard
                </a>
            </li>

            {{-- STUDENT SIDEBAR --}}
            @if(Auth::user()->role === 'student')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.teams.*') ? 'active' : '' }}" href="{{ route('student.teams.index') }}">
                        👥 My Team
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.project.*') ? 'active' : '' }}" href="{{ route('student.project.index') }}">
                        📄 Project Proposal
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.documents.*') ? 'active' : '' }}" href="{{ route('student.documents.index') }}">
                        📁 Documents Upload
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.milestones.*') ? 'active' : '' }}" href="{{ route('student.milestones.index') }}">
                        📊 Milestones / Progress
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.feedback.*') ? 'active' : '' }}" href="{{ route('student.feedback.index') }}">
                        💬 Supervisor Feedback
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.presentations.*') ? 'active' : '' }}" href="{{ route('student.presentations.index') }}">
                        🗓️ Presentation Schedule
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.announcements') ? 'active' : '' }}" href="{{ route('public.announcements') }}">
                        📢 Announcements
                    </a>
                </li>
            @endif

            {{-- SUPERVISOR SIDEBAR --}}
            @if(Auth::user()->role === 'supervisor')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('supervisor.assignedTeams.index') }}">
                        👥 Assigned Teams
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('supervisor.proposals.*') ? 'active' : '' }}" href="{{ route('supervisor.proposals.index') }}">
                        📄 Proposal Review
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('supervisor.documents.*') ? 'active' : '' }}" href="{{ route('supervisor.documents.index') }}">
                        📁 Student Documents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('supervisor.evaluation.index') }}">
                        📝 Evaluation / Marks
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('supervisor.progress.*') ? 'active' : '' }}" href="{{ route('supervisor.progress.index') }}">
                        📈 Progress Monitoring
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('supervisor.communication.index') }}">
                        💬 Communication / Feedback
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('supervisor.meetings.index') }}">
                        🗓️ Meetings / Schedule
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.announcements') ? 'active' : '' }}" href="{{ route('public.announcements') }}">
                        📢 Announcements
                    </a>
                </li>
            @endif

            {{-- ADMIN SIDEBAR --}}
            @if(Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        👥 User Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.domains.*') ? 'active' : '' }}" href="{{ route('admin.domains.index') }}">
                        📚 Project Domains
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}" href="{{ route('admin.teams.index') }}">
                        👥 Team Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
                        📄 Proposal Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}" href="{{ route('admin.documents.index') }}">
                        📁 All Documents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.evaluations.index') }}">
                        📝 Evaluation Records
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.semesters.*') ? 'active' : '' }}" href="{{ route('admin.semesters.index') }}">
                        🗓️ Semester & Deadlines
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.announcements') ? 'active' : '' }}" href="{{ route('public.announcements') }}">
                        📢 Announcements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.reports') }}">
                        📊 Reports / Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.archive.index') }}">
                        📦 Archive
                    </a>
                </li>
            @endif

        </ul>
    </nav>
</aside>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarCloseBtn');
        const toggleIcon = document.getElementById('toggleIcon');

        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);

        // Toggle sidebar from external button
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            toggleIcon.textContent = '✕';
        });

        // Close sidebar from close button inside sidebar
        closeBtn.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            toggleIcon.textContent = '☰';
        });

        // Close sidebar when overlay is clicked
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            toggleIcon.textContent = '☰';
        });

        // Close sidebar when a link is clicked (on mobile)
        const navLinks = sidebar.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    toggleIcon.textContent = '☰';
                }
            });
        });

        // Close sidebar on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    });
</script> -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarCloseBtn');
    const toggleIcon = document.getElementById('toggleIcon');

    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    // Open sidebar
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.add('active');
        overlay.classList.add('active');

        // Hide hamburger button
        toggleBtn.style.display = 'none';
    });

    // Close sidebar
    closeBtn.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');

        // Show hamburger button again
        toggleBtn.style.display = 'flex';
    });

    // Overlay click
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');

        // Show hamburger button again
        toggleBtn.style.display = 'flex';
    });

    // Nav link click
    const navLinks = sidebar.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');

                // Show hamburger button again
                toggleBtn.style.display = 'flex';
            }
        });
    });

    // Resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');

            // Keep button visible on desktop
            toggleBtn.style.display = 'flex';
        }
    });
});
</script>
