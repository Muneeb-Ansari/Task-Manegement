<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Task Management') }}</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')
        {{-- optional: mobile toggle button --}}
        <button class="btn btn-outline-secondary d-md-none m-2" type="button" data-bs-toggle="collapse"
            data-bs-target="#appSidebar" aria-controls="appSidebar" aria-expanded="false" aria-label="Toggle sidebar">
            ☰ Menu
        </button>

        <div class="container-fluid">
            <div class="row min-vh-100">
                {{-- Sidebar --}}
                @include('layouts.partials.sidebar')

                {{-- Main content --}}
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    @include('layouts.partials.alerts')
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>
