<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnify - Sistem Pencatatan Aktivitas Mengajar Harian</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts (Manrope) & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Manrope', sans-serif !important;
            background: linear-gradient(135deg, #252e56 0%, #2b386b 50%, #3d4a82 100%) !important;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .login-card {
            background: #ffffff !important;
            border-radius: 28px !important;
            border: none !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25) !important;
            padding: 44px 32px 36px 32px;
            width: 100%;
            max-width: 420px;
        }

        .brand-icon-wrapper {
            width: 64px;
            height: 64px;
            background-color: #f0f4ff;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
        }

        .brand-icon-wrapper .material-symbols-outlined {
            font-size: 32px;
            color: #2b386b;
        }

        .brand-title {
            color: #1a1e36;
            font-weight: 800;
            font-size: 28px;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            color: #718096;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 32px;
            line-height: 1.45;
        }

        .form-label {
            font-size: 13.5px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group-custom .input-icon-left {
            position: absolute;
            left: 16px;
            color: #8c9ba5;
            font-size: 20px;
            pointer-events: none;
            z-index: 5;
        }

        .input-group-custom .input-icon-right {
            position: absolute;
            right: 16px;
            color: #8c9ba5;
            font-size: 20px;
            cursor: pointer;
            z-index: 5;
            background: none;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .input-group-custom .form-control {
            font-family: 'Manrope', sans-serif !important;
            padding-left: 48px !important;
            padding-right: 48px !important;
            height: 52px !important;
            border-radius: 12px !important;
            border: 1px solid #e2e8f0 !important;
            font-size: 14px;
            font-weight: 500;
            color: #2d3748;
            background-color: #fafbfc !important;
        }

        .input-group-custom .form-control:focus {
            background-color: #ffffff !important;
            border-color: #2b386b !important;
            box-shadow: 0 0 0 3.5px rgba(43, 56, 107, 0.12) !important;
        }

        .btn-login {
            font-family: 'Manrope', sans-serif !important;
            background-color: #2b386b !important;
            border: none !important;
            height: 52px !important;
            border-radius: 12px !important;
            font-weight: 700;
            font-size: 16px;
            color: #ffffff !important;
            margin-top: 8px;
        }

        .btn-login:hover {
            background-color: #1e2850 !important;
        }

        @media (max-width: 575.98px) {
            .login-card {
                padding: 36px 24px 28px 24px;
                border-radius: 24px !important;
            }

            .brand-icon-wrapper {
                width: 58px;
                height: 58px;
                margin-bottom: 16px;
            }

            .brand-title {
                font-size: 24px;
            }

            .brand-subtitle {
                font-size: 13px;
                margin-bottom: 24px;
            }

            .input-group-custom .form-control,
            .btn-login {
                height: 48px !important;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Icon Logo -->
            <div class="brand-icon-wrapper">
                <span class="material-symbols-outlined">menu_book</span>
            </div>

            <!-- Header Info -->
            <div class="text-center">
                <h4 class="brand-title">Jurnify</h4>
                <p class="brand-subtitle">Sistem Pencatatan Aktivitas Mengajar Harian</p>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 rounded-3 mb-3">
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Username -->
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group-custom">
                        <span class="material-symbols-outlined input-icon-left">person</span>
                        <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus autocomplete="username">
                    </div>
                </div>

                <!-- Kata Sandi -->
                <div class="mb-4">
                    <label class="form-label">Kata Sandi</label>
                    <div class="input-group-custom">
                        <span class="material-symbols-outlined input-icon-left">lock</span>
                        <input type="password" id="passwordInput" name="password" class="form-control" placeholder="Masukkan kata sandi" required autocomplete="current-password">
                        <button type="button" class="input-icon-right" id="togglePassword" aria-label="Tampilkan Kata Sandi">
                            <span class="material-symbols-outlined" id="toggleIcon">visibility_off</span>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login w-100">Log In</button>
            </form>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');
        const toggleIcon = document.getElementById('toggleIcon');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.textContent = isPassword ? 'visibility' : 'visibility_off';
        });
    </script>
</body>
</html>