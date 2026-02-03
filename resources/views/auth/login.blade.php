<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>เข้าสู่ระบบ - ระบบงานธุรการโรงเรียนพลาธิการ</title>
    <meta name="description"
        content="ระบบงานธุรการโรงเรียนพลาธิการ กรมพลาธิการทหารเรือ - Royal Thai Navy Administration System">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            /* Navy Color System */
            --navy-900: #0a1628;
            --navy-800: #0f2744;
            --navy-700: #1a3a5c;
            --navy-600: #2563eb;
            --navy-500: #3b82f6;
            --navy-400: #60a5fa;
            --navy-300: #93c5fd;

            /* Neutral System */
            --white: #ffffff;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;

            /* Accent */
            --accent-gold: #d4a853;
            --accent-success: #10b981;
            --accent-error: #ef4444;

            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);

            /* Spacing */
            --space-1: 0.25rem;
            --space-2: 0.5rem;
            --space-3: 0.75rem;
            --space-4: 1rem;
            --space-5: 1.25rem;
            --space-6: 1.5rem;
            --space-8: 2rem;
            --space-10: 2.5rem;
            --space-12: 3rem;

            /* Border Radius */
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --radius-full: 9999px;

            /* Transitions */
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-normal: 200ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 300ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Sarabun', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--navy-900) 0%, var(--navy-800) 50%, var(--navy-700) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--space-4);
            position: relative;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Background Pattern */
        .bg-pattern {
            position: fixed;
            inset: 0;
            z-index: 0;
            opacity: 0.03;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* Ambient Light Effects */
        .ambient-light {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-light-1 {
            width: 400px;
            height: 400px;
            background: var(--navy-600);
            opacity: 0.15;
            top: -100px;
            right: -100px;
            animation: float 20s ease-in-out infinite;
        }

        .ambient-light-2 {
            width: 300px;
            height: 300px;
            background: var(--navy-500);
            opacity: 0.1;
            bottom: -50px;
            left: -50px;
            animation: float 25s ease-in-out infinite reverse;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(30px, 20px) scale(1.1);
            }
        }

        /* Main Container */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: var(--space-8);
        }

        .logo-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: var(--space-5);
        }

        .logo-glow {
            position: absolute;
            inset: -20px;
            background: radial-gradient(circle, var(--navy-400) 0%, transparent 70%);
            opacity: 0.2;
            border-radius: 50%;
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.2;
                transform: scale(1);
            }

            50% {
                opacity: 0.3;
                transform: scale(1.05);
            }
        }

        .logo-image {
            position: relative;
            width: 100px;
            height: 100px;
            object-fit: contain;
            filter: drop-shadow(0 4px 20px rgba(0, 0, 0, 0.3));
        }

        .brand-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 0.02em;
            margin-bottom: var(--space-2);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .brand-subtitle {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-3);
            color: var(--navy-300);
            font-size: 0.875rem;
            font-weight: 400;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .brand-line {
            width: 32px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--navy-400), transparent);
        }

        /* Card */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-2xl), 0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }

        .card-body {
            padding: var(--space-10);
        }

        /* Status Message */
        .status-message {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-4);
            margin-bottom: var(--space-6);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: var(--radius-lg);
            color: #047857;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            color: var(--accent-success);
        }

        /* Form */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: var(--space-6);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: var(--space-2);
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            padding-left: var(--space-1);
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: var(--space-4);
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--gray-400);
            transition: color var(--transition-fast);
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 52px;
            padding: 0 var(--space-4) 0 calc(var(--space-4) + 28px);
            font-family: inherit;
            font-size: 0.9375rem;
            color: var(--gray-900);
            background: var(--gray-50);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-xl);
            outline: none;
            transition: all var(--transition-fast);
        }

        .form-input::placeholder {
            color: var(--gray-400);
        }

        .form-input:hover {
            border-color: var(--gray-300);
            background: var(--white);
        }

        .form-input:focus {
            border-color: var(--navy-500);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .form-input:focus+.input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--navy-500);
        }

        /* Error Message */
        .error-message {
            display: flex;
            align-items: center;
            gap: var(--space-1);
            margin-top: var(--space-2);
            padding-left: var(--space-1);
            font-size: 0.8125rem;
            color: var(--accent-error);
        }

        .error-icon {
            flex-shrink: 0;
            width: 14px;
            height: 14px;
        }

        /* Checkbox & Forgot Password Row */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: var(--space-3);
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            cursor: pointer;
        }

        .checkbox-input {
            width: 18px;
            height: 18px;
            accent-color: var(--navy-600);
            cursor: pointer;
            border-radius: var(--radius-sm);
        }

        .checkbox-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            cursor: pointer;
            user-select: none;
            transition: color var(--transition-fast);
        }

        .checkbox-wrapper:hover .checkbox-label {
            color: var(--gray-800);
        }

        .forgot-link {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--navy-600);
            text-decoration: none;
            transition: all var(--transition-fast);
        }

        .forgot-link:hover {
            color: var(--navy-700);
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        /* Submit Button */
        .submit-btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            width: 100%;
            height: 56px;
            margin-top: var(--space-2);
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            color: var(--white);
            background: linear-gradient(135deg, var(--navy-700) 0%, var(--navy-800) 100%);
            border: none;
            border-radius: var(--radius-xl);
            cursor: pointer;
            overflow: hidden;
            transition: all var(--transition-normal);
            box-shadow: 0 4px 15px rgba(10, 22, 40, 0.3);
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--navy-600) 0%, var(--navy-700) 100%);
            opacity: 0;
            transition: opacity var(--transition-fast);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(10, 22, 40, 0.4);
        }

        .submit-btn:hover::before {
            opacity: 1;
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:focus {
            outline: none;
            box-shadow: 0 4px 15px rgba(10, 22, 40, 0.3), 0 0 0 4px rgba(59, 130, 246, 0.3);
        }

        .submit-btn span {
            position: relative;
            z-index: 1;
        }

        .submit-btn-icon {
            position: relative;
            z-index: 1;
            width: 20px;
            height: 20px;
            transition: transform var(--transition-fast);
        }

        .submit-btn:hover .submit-btn-icon {
            transform: translateX(4px);
        }

        /* Card Footer */
        .card-footer {
            padding: var(--space-4) var(--space-6);
            background: var(--gray-50);
            border-top: 1px solid var(--gray-100);
            text-align: center;
        }

        .footer-text {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--gray-400);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* Bottom Credit */
        .credit-text {
            margin-top: var(--space-6);
            text-align: center;
            font-size: 0.75rem;
            color: rgba(147, 197, 253, 0.4);
            font-weight: 300;
        }

        /* Responsive */
        @media (max-width: 480px) {
            body {
                padding: var(--space-4);
                align-items: flex-start;
                padding-top: var(--space-8);
            }

            .logo-image {
                width: 80px;
                height: 80px;
            }

            .brand-title {
                font-size: 1.5rem;
            }

            .card-body {
                padding: var(--space-6);
            }

            .form-input {
                height: 48px;
                font-size: 1rem;
                /* Prevent zoom on iOS */
            }

            .submit-btn {
                height: 52px;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--space-4);
            }

            .forgot-link {
                align-self: flex-start;
            }
        }

        @media (max-width: 360px) {
            .brand-subtitle {
                font-size: 0.75rem;
            }

            .brand-line {
                width: 20px;
            }
        }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .login-container {
                animation: none;
                opacity: 1;
                transform: none;
            }

            .ambient-light-1,
            .ambient-light-2,
            .logo-glow {
                animation: none;
            }

            .submit-btn,
            .submit-btn::before,
            .submit-btn-icon,
            .form-input,
            .input-icon {
                transition: none;
            }
        }
    </style>
</head>

<body>
    <!-- Background Elements -->
    <div class="bg-pattern"></div>
    <div class="ambient-light ambient-light-1"></div>
    <div class="ambient-light ambient-light-2"></div>

    <!-- Main Container -->
    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-wrapper">
                <div class="logo-glow"></div>
                <img class="logo-image" src="{{ asset('images/logonavy.png') }}" alt="ตราสัญลักษณ์กองทัพเรือ">
            </div>
            <h1 class="brand-title">ระบบงานธุรการ</h1>
            <div class="brand-subtitle">
                <span class="brand-line"></span>
                <span>Naval Supply School</span>
                <span class="brand-line"></span>
            </div>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <div class="card-body">
                @if (session('status'))
                    <div class="status-message">
                        <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">อีเมลทางการทหาร</label>
                        <div class="input-wrapper">
                            <input id="email" name="email" type="email" class="form-input"
                                placeholder="example@navy.mi.th" value="{{ old('email') }}" autocomplete="email"
                                required autofocus>
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <div class="input-wrapper">
                            <input id="password" name="password" type="password" class="form-input"
                                placeholder="••••••••" autocomplete="current-password" required>
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Options Row -->
                    <div class="form-options">
                        <label class="checkbox-wrapper">
                            <input id="remember_me" name="remember" type="checkbox" class="checkbox-input">
                            <span class="checkbox-label">จดจำการใช้งาน</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                ลืมรหัสผ่าน?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">
                        <span>เข้าสู่ระบบ</span>
                        <svg class="submit-btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>
            </div>

            <div class="card-footer">
                <p class="footer-text">Royal Thai Navy — Administration System © {{ date('Y') }}</p>
            </div>
        </div>

        <!-- Credit -->
        <p class="credit-text">ออกแบบและพัฒนาระบบโดย จ.ท.ธีร์ธวัช พิพัฒน์เดชธน</p>
    </div>
</body>

</html>