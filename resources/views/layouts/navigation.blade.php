{{-- resources/views/layouts/navigation.blade.php --}}
<ul class="nav flex-column">
    @if(auth()->user()->isAdmin())
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.modules.*') ? 'active' : '' }}" href="{{ route('admin.modules.index') }}">
                <i class="bi bi-book"></i> Modules
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}" href="{{ route('admin.teachers.index') }}">
                <i class="bi bi-person-badge"></i> Teachers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                <i class="bi bi-people"></i> Students
            </a>
        </li>
    @elseif(auth()->user()->isTeacher())
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        @if(auth()->user()->isStudent())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.enroll.*') ? 'active' : '' }}" href="{{ route('student.enroll.index') }}">
                    <i class="bi bi-plus-circle"></i> Enroll in Module
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.history') ? 'active' : '' }}" href="{{ route('student.history') }}">
                <i class="bi bi-clock-history"></i> Module History
            </a>
        </li>
    @endif
</ul>