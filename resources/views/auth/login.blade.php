<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hosit Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-yellow: #FFD700;
            --light-yellow: #FFF9E6;
            --dark-yellow: #E6C200;
            --text-dark: #333333;
        }
        
        body {
            background: linear-gradient(135deg, var(--light-yellow) 0%, #FFFEF5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(230, 194, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        
        .login-left {
            background: linear-gradient(135deg, var(--primary-yellow) 0%, var(--dark-yellow) 100%);
            padding: 60px 40px;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .login-left i {
            font-size: 80px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .login-left h2 {
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
        }
        
        .login-left p {
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .login-right {
            padding: 60px 40px;
        }
        
        .login-right h3 {
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-right p {
            color: #666;
            margin-bottom: 30px;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
        }
        
        .input-group-text {
            background-color: var(--light-yellow);
            border: 2px solid #e0e0e0;
            border-right: none;
            color: var(--text-dark);
        }
        
        .input-group .form-control {
            border-left: none;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-yellow), var(--dark-yellow));
            border: none;
            color: var(--text-dark);
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            box-shadow: 0 4px 15px rgba(230, 194, 0, 0.3);
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(230, 194, 0, 0.4);
            background: linear-gradient(135deg, var(--dark-yellow), var(--primary-yellow));
        }
        
        .alert {
            border: none;
            border-radius: 10px;
            border-left: 4px solid #dc3545;
        }
        
        .demo-credentials {
            background-color: var(--light-yellow);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid var(--primary-yellow);
        }
        
        .demo-credentials h6 {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .demo-credentials small {
            display: block;
            margin: 5px 0;
            color: #666;
        }
        
        .demo-credentials code {
            background-color: white;
            padding: 2px 6px;
            border-radius: 4px;
            color: var(--text-dark);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card row g-0">
            <div class="col-md-5 login-left">
                <i class="bi bi-shop"></i>
                <h2>Hosit Restaurant</h2>
                <p>Sistem Manajemen Restoran dengan Pencatatan Keuangan yang Efisien</p>
                <div class="mt-4">
                    <i class="bi bi-check-circle"></i> Pencatatan Pemasukan Otomatis<br>
                    <i class="bi bi-check-circle"></i> Pencatatan Pengeluaran<br>
                    <i class="bi bi-check-circle"></i> Laporan Keuangan Real-time
                </div>
            </div>
            
            <div class="col-md-7 login-right">
                <h3>Selamat Datang!</h3>
                <p>Silakan login untuk melanjutkan</p>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   placeholder="Masukkan email"
                                   value="{{ old('email') }}"
                                   required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Masukkan password"
                                   required>
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>
                
                <div class="demo-credentials">
                    <h6><i class="bi bi-info-circle"></i> Demo Credentials:</h6>
                    <small><strong>Admin:</strong> <code>admin@restaurant.com</code> / <code>admin123</code></small>
                    <small><strong>Kasir:</strong> <code>kasir@restaurant.com</code> / <code>kasir123</code></small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
