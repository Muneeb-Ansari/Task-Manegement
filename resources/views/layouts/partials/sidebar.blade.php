<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ url('/') }}">Dashboard</a>
            </li>
            @can('viewAny', App\Models\User::class)
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/users') }}">Users</a>
                </li>
            @endcan
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/tasks') }}">Tasks</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="{{ url('/orders') }}">Orders</a>
            </li> --}}
        </ul>
    </div>
</nav>
