<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Restaurant Management') - Hosit</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-yellow: #FFD700;
            --light-yellow: #FFF9E6;
            --dark-yellow: #E6C200;
            --text-dark: #333333;
            --hover-yellow: #FFED4E;
        }
        
        body {
            background-color: var(--light-yellow);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-yellow) 0%, var(--dark-yellow) 100%);
            box-shadow: 0 2px 10px rgba(230, 194, 0, 0.3);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-dark) !important;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
        }
        
        .navbar-nav .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            margin: 0 5px;
            transition: all 0.3s;
        }
        
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            transform: translateY(-2px);
        }
        
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 5px;
        }
        
        .sidebar {
            background-color: #FFFBF0;
            min-height: calc(100vh - 56px);
            padding: 20px 10px;
            border-right: 2px solid var(--primary-yellow);
        }
        
        .sidebar .nav-link {
            color: var(--text-dark);
            padding: 12px 15px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .sidebar .nav-link:hover {
            background-color: var(--hover-yellow);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-yellow), var(--dark-yellow));
            color: var(--text-dark);
            box-shadow: 0 3px 8px rgba(230, 194, 0, 0.4);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(230, 194, 0, 0.2);
            background-color: white;
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-yellow), var(--dark-yellow));
            color: var(--text-dark);
            font-weight: 600;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-yellow), var(--dark-yellow));
            border: none;
            color: var(--text-dark);
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(230, 194, 0, 0.3);
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-yellow), var(--primary-yellow));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 194, 0, 0.4);
            color: var(--text-dark);
        }
        
        .btn-success {
            background-color: #28a745;
            border: none;
            box-shadow: 0 3px 8px rgba(40, 167, 69, 0.3);
        }
        
        .btn-danger {
            background-color: #dc3545;
            border: none;
            box-shadow: 0 3px 8px rgba(220, 53, 69, 0.3);
        }
        
        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary-yellow), var(--dark-yellow));
            color: var(--text-dark);
            font-weight: 600;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #FFFEF5;
        }
        
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 6px;
        }
        
        .badge-warning {
            background-color: var(--primary-yellow);
            color: var(--text-dark);
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(230, 194, 0, 0.2);
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(230, 194, 0, 0.3);
        }
        
        .stat-card .icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-yellow), var(--dark-yellow));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--text-dark);
            box-shadow: 0 3px 10px rgba(230, 194, 0, 0.3);
        }
        
        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 10px 0 5px 0;
        }
        
        .stat-card .label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
        }
        
        .page-link {
            color: var(--text-dark);
        }
        
        .page-link:hover {
            background-color: var(--hover-yellow);
            color: var(--text-dark);
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-yellow);
            border-color: var(--primary-yellow);
            color: var(--text-dark);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-shop"></i> Hosit Restaurant
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-person-circle"></i> 
                            {{ auth()->user()->name }} 
                            <span class="badge bg-dark">{{ ucfirst(auth()->user()->role) }}</span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link" style="color: var(--text-dark) !important;">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('pos') ? 'active' : '' }}" href="{{ route('pos') }}">
                        <i class="bi bi-cart-check"></i> POS
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                        <i class="bi bi-receipt"></i> Transaksi
                    </a>
                    
                    @if(auth()->user()->isAdmin())
                        <hr style="border-color: var(--primary-yellow);">
                        <small class="text-muted px-3 mb-2">ADMIN</small>
                        
                        <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                            <i class="bi bi-cash-stack"></i> Pengeluaran
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <i class="bi bi-graph-up"></i> Laporan
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-box-seam"></i> Produk
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                        
                        

                        <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                            <i class="bi bi-people"></i> Karyawan
                        </a>

                        <a class="nav-link {{ request()->routeIs('employees.attendance-log') ? 'active' : '' }}" href="{{ route('employees.attendance-log') }}">
                            <i class="bi bi-calendar-check"></i> Absensi
                        </a>
                    @endif
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
