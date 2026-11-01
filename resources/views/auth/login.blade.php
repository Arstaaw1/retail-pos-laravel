<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Enterprise Retail POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card p-4 bg-white">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">Retail POS System</h4>
                    <small class="text-muted">Masuk untuk mengelola inventaris & penjualan</small>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 small mb-3">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-start-0" placeholder="nama@tokoritai.com" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-3 mb-3">Masuk Sistem</button>
                </form>

            
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>