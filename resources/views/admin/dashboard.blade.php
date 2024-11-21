<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <a href="{{ url('/admin') }}">Dashboard</a>
            <a href="{{ url('/admin/products') }}">Products</a>
            <a href="{{ url('/admin/categories') }}">Categories</a>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Header -->
            <div class="header">
                <div class="user-info">
                    Welcome, Admin
                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
