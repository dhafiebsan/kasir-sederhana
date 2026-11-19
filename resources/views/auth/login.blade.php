<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Aplikasi Kasir Sederhana</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            background: #ffffff;
        }

        .card-header {
            background: white;
            border-bottom: none;
            padding-top: 2.2rem;
            padding-bottom: 1rem;
        }

        .brand-logo {
            width: 65px;
            height: 65px;
            background: var(--primary-color);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.3);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .form-control:focus {
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            border-color: var(--primary-color);
        }

        .btn-login {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: #ffffff;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: var(--primary-hover);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.25);
        }

        .login-footer {
            font-size: 0.85rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card p-3">
                    <div class="card-header text-center">
                        <div class="brand-logo">
                            <i class="fa-solid fa-shop"></i>
                        </div>
                        <h4 class="fw-bold mb-1 text-dark">Kasir Sederhana</h4>
                        <p class="text-muted small">Silakan masuk untuk akses transaksi & dashboard</p>
                    </div>
                    <div class="card-body px-3 pt-0">
                        @if(session('error'))
                            <div class="alert alert-danger border-0 small d-flex align-items-center mb-3">
                                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ url('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Alamat Email</label>
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required autofocus>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-secondary">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                            <button type="submit" class="btn btn-login w-100">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk Sekarang
                            </button>
                        </form>
                    </div>
                    <div class="card-footer bg-white border-0 text-center pb-3">
                        <p class="login-footer mb-0">&copy; 2026 Kasir Sederhana &bull; POS System</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
